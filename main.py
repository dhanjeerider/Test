import requests
from bs4 import BeautifulSoup
import random
from time import sleep
import logging
import base64
import json
from concurrent.futures import ThreadPoolExecutor
import dateparser

# Setup logging
logging.basicConfig(level=logging.INFO, format="%(asctime)s - %(levelname)s - %(message)s")

# WordPress API setup
WP_API_URL = "https://limegreen-boar-354339.hostingersite.com/wp-json/wp/v2/posts"
WP_MEDIA_URL = "https://limegreen-boar-354339.hostingersite.com/wp-json/wp/v2/media"
WP_CATEGORIES_URL = "https://limegreen-boar-354339.hostingersite.com/wp-json/wp/v2/categories"
WP_USERNAME = "admin"
WP_PASSWORD = "22Tm kPci 1GpE MZng TZvO Ewsb"

# User-Agent list
USER_AGENTS = [
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36",
    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
]

# Session setup
session = requests.Session()
session.auth = requests.auth.HTTPBasicAuth(WP_USERNAME, WP_PASSWORD)

# Fetch existing titles from WordPress
def fetch_existing_titles():
    """Fetch existing post titles from WordPress."""
    existing_titles = set()
    page = 1
    while True:
        response = session.get(WP_API_URL, params={"per_page": 100, "page": page})
        if response.status_code != 200 or not response.json():
            break
        for post in response.json():
            existing_titles.add(post['title']['rendered'].strip())
        page += 1
    logging.info(f"Fetched {len(existing_titles)} existing post titles.")
    return existing_titles

def fetch_url(url):
    """Fetch a URL with retries and User-Agent rotation."""
    for attempt in range(3):
        try:
            headers = {"User-Agent": random.choice(USER_AGENTS)}
            response = session.get(url, headers=headers, timeout=10)
            response.raise_for_status()
            return response
        except requests.exceptions.RequestException as e:
            delay = random.uniform(1, 3)
            logging.warning(f"Retrying {url} after {delay:.1f} seconds (Attempt {attempt + 1}): {e}")
            sleep(delay)
    logging.error(f"Failed to fetch URL after 3 attempts: {url}")
    return None

def upload_image_to_wordpress(image_url):
    """Upload an image to WordPress and return its media ID."""
    try:
        response = fetch_url(image_url)
        if not response:
            return None

        auth_header = base64.b64encode(f"{WP_USERNAME}:{WP_PASSWORD}".encode('utf-8')).decode('utf-8')
        headers = {
            'Authorization': f'Basic {auth_header}',
            'Content-Disposition': f'attachment; filename="{image_url.split("/")[-1]}"',
        }
        files = {'file': (image_url.split("/")[-1], response.content)}

        upload_response = session.post(WP_MEDIA_URL, headers=headers, files=files)
        if upload_response.status_code == 201:
            return upload_response.json().get('id')
    except Exception as e:
        logging.error(f"Error uploading image {image_url}: {e}")
    return None

def get_or_create_category(category_name):
    """Get existing category ID or create new one."""
    try:
        # Check if category exists
        response = session.get(WP_CATEGORIES_URL, params={'search': category_name})
        if response.status_code == 200 and response.json():
            return response.json()[0]['id']

        # Create new category
        auth_header = base64.b64encode(f"{WP_USERNAME}:{WP_PASSWORD}".encode('utf-8')).decode('utf-8')
        headers = {'Authorization': f'Basic {auth_header}', 'Content-Type': 'application/json'}
        data = {'name': category_name}
        create_response = session.post(WP_CATEGORIES_URL, headers=headers, json=data)
        if create_response.status_code == 201:
            return create_response.json()['id']
    except Exception as e:
        logging.error(f"Failed to get/create category '{category_name}': {e}")
    return None

def extract_categories(soup):
    """Extract category names from .thecategory div."""
    category_tag = soup.select_one('.thecategory a')
    if category_tag:
        main_category = category_tag.text.strip()
        sub_category = None  # You can set this if needed
        return main_category, sub_category
    return None, None

def upload_to_wordpress(post_data):
    """Upload a post to WordPress."""
    wp_post_data = {
        'title': post_data['title'],
        'content': post_data['content'],
        'status': 'publish',
        'date': post_data.get('published_date') if post_data.get('published_date') else None,
    }

    if post_data.get('featured_image'):
        media_id = upload_image_to_wordpress(post_data['featured_image'])
        if media_id:
            wp_post_data['featured_media'] = media_id

    if post_data.get('categories'):
        wp_post_data['categories'] = post_data['categories']

    try:
        auth_header = base64.b64encode(f"{WP_USERNAME}:{WP_PASSWORD}".encode('utf-8')).decode('utf-8')
        headers = {'Authorization': f'Basic {auth_header}', 'Content-Type': 'application/json'}

        response = session.post(WP_API_URL, json=wp_post_data, headers=headers)
        if response.status_code == 201:
            logging.info(f"Post '{post_data['title']}' uploaded successfully!")
        else:
            logging.error(f"Failed to upload post '{post_data['title']}'. Response: {response.text}")
    except Exception as e:
        logging.error(f"Error uploading post '{post_data['title']}': {e}")

def scrape_page(url):
    response = fetch_url(url)
    if not response:
        return []

    soup = BeautifulSoup(response.content, 'html.parser')
    posts = []

    for article in soup.find_all('article', class_='latestPost excerpt'):
        try:
            post_link = article.find('a')['href']
            post_title = article.find('h2', class_='title front-view-title').text.strip()

            # Extract featured image URL
            featured_thumbnail = article.find('div', class_='featured-thumbnail')
            if featured_thumbnail and featured_thumbnail.find('img'):
                featured_image_url = featured_thumbnail.find('img')['src']
            else:
                featured_image_url = None

            posts.append({'link': post_link, 'title': post_title, 'featured_image_url': featured_image_url})
        except Exception as e:
            logging.error(f"Error extracting data from article: {e}")
            continue
    return posts

def scrape_post(post_url):
    response = fetch_url(post_url)
    if not response:
        return None

    soup = BeautifulSoup(response.content, 'html.parser')
    content_div = soup.find('div', class_='entry-content')
    if not content_div:
        return None

    post_title = soup.find('h1', class_='title single-title entry-title').text.strip() if soup.find('h1', class_='title single-title entry-title') else "No Title"
   
 # Featured image: og:image or fallback to .fimg img
    featured_image = None
    og_image = soup.find('meta', property="og:image")
    if og_image and og_image.get("content"):
        featured_image = og_image["content"]
    else:
        fimg_tag = soup.select_one("article.latestPost .featured-thumbnail img")
        if fimg_tag and fimg_tag.get("src"):
            featured_image = fimg_tag["src"]

    # Extract categories
    main_cat, sub_cat = extract_categories(soup)
    categories = []
    if main_cat:
        main_cat_id = get_or_create_category(main_cat)
        if main_cat_id:
            categories.append(main_cat_id)
    if sub_cat:
        sub_cat_id = get_or_create_category(sub_cat)
        if sub_cat_id and sub_cat_id not in categories:
            categories.append(sub_cat_id)

# Published date from .thetime or meta[property="article:published_time"]
    published_date = None

    # Try .thetime
    published_date_tag = soup.select_one('.post-info .thetime span')
    if published_date_tag:
        published_date_text = published_date_tag.text.strip()
        try:
            published_date = dateparser.parse(published_date_text)
        except:
            published_date = None

    # Fallback to meta tag
    if not published_date:
        meta_time = soup.find("meta", property="article:published_time")
        if meta_time and meta_time.get("content"):
            try:
                published_date = dateparser.parse(meta_time["content"])
            except:
                published_date = None

    if published_date:
        published_date = published_date.isoformat()
        

    return {
        'title': post_title,
        'content': str(content_div),
        'featured_image': featured_image['content'] if featured_image else None,
        'published_date': published_date,
        'categories': categories
    }

def main():
    """Main function to scrape and upload posts."""
    base_url = "https://themoviesflix.ag/"
    start_page = 1
    end_page = 300

    existing_titles = fetch_existing_titles()

    logging.info(f"Scraping pages from {start_page} to {end_page}")

    with ThreadPoolExecutor(max_workers=15) as executor:
        for page in range(start_page, end_page + 1):
            page_url = f"{base_url}page/{page}/"
            posts = scrape_page(page_url)
            logging.info(f"Scraped {len(posts)} posts from {page_url}")

            for post in posts:
                if post['title'] in existing_titles:
                    logging.info(f"Skipping duplicate post: {post['title']}")
                    continue

                post_data = scrape_post(post['link'])
                if post_data:
                    logging.info(f"Uploading post: {post_data['title']}")
                    executor.submit(upload_to_wordpress, post_data)
                    existing_titles.add(post_data['title'])

if __name__ == "__main__":
    main()
