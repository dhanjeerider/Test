#!/usr/bin/env node

/**
 * Demo Data Generator for NewsCMS
 * Populates the database with sample posts, pages, and settings
 */

const API_BASE = process.env.API_BASE || 'http://localhost:3000'

const colors = {
  reset: '\x1b[0m',
  bright: '\x1b[1m',
  green: '\x1b[32m',
  red: '\x1b[31m',
  yellow: '\x1b[33m',
  blue: '\x1b[34m',
  cyan: '\x1b[36m',
}

let authCookie = ''

function log(message, color = colors.reset) {
  console.log(`${color}${message}${colors.reset}`)
}

async function makeRequest(endpoint, options = {}) {
  const url = `${API_BASE}${endpoint}`
  const headers = {
    'Content-Type': 'application/json',
    ...options.headers,
  }
  
  if (authCookie) {
    headers['Cookie'] = authCookie
  }

  try {
    const response = await fetch(url, { ...options, headers })
    
    if (response.headers.get('set-cookie')) {
      authCookie = response.headers.get('set-cookie')
    }
    
    const contentType = response.headers.get('content-type')
    let data = null
    
    if (contentType && contentType.includes('application/json')) {
      data = await response.json()
    }
    
    return { status: response.status, data, response }
  } catch (error) {
    return { status: 0, error: error.message }
  }
}

// Sample posts
const samplePosts = [
  {
    title: 'Breaking: AI Revolutionizes Content Creation',
    content: `In a groundbreaking development, artificial intelligence is transforming the way content is created and consumed across digital platforms. Industry experts predict this will fundamentally change the media landscape.

Recent studies show that AI-powered tools are helping writers and creators produce higher quality content more efficiently. This technological advancement is democratizing content creation, allowing smaller publishers to compete with major media outlets.

The implications are far-reaching, affecting everything from journalism to marketing to entertainment. As AI continues to evolve, we can expect even more dramatic changes in how we create and consume information.`,
    excerpt: 'Artificial intelligence is transforming content creation across digital platforms, democratizing media production.',
    status: 'published',
    category: 'Technology',
    tags: 'AI,Innovation,Media',
    is_trending_post: true,
  },
  {
    title: 'Global Climate Summit Reaches Historic Agreement',
    content: `World leaders have reached a landmark agreement at the latest climate summit, committing to unprecedented measures to combat climate change. The agreement includes specific targets for carbon reduction and renewable energy adoption.

This historic deal represents years of negotiation and marks a turning point in global climate policy. Countries have pledged to invest billions in green technology and sustainable infrastructure.

Environmental activists are cautiously optimistic, noting that while the agreement is a significant step forward, implementation will be crucial. The coming years will determine whether these commitments translate into meaningful action.`,
    excerpt: 'World leaders commit to unprecedented climate action in historic international agreement.',
    status: 'published',
    category: 'Environment',
    tags: 'Climate,Politics,Environment',
    is_trending_post: true,
  },
  {
    title: 'Tech Giant Unveils Revolutionary Smartphone',
    content: `The latest smartphone from a leading tech company is pushing the boundaries of mobile technology. Featuring advanced AI capabilities, improved battery life, and innovative design, the device is setting new standards for the industry.

Pre-orders have already broken records, indicating strong consumer demand. Analysts predict this could be the company's most successful product launch in years.

The phone includes several groundbreaking features that weren't available before, including enhanced privacy controls and seamless integration with smart home devices. Industry watchers believe this could trigger a wave of innovation across the entire smartphone market.`,
    excerpt: 'New flagship smartphone features groundbreaking technology and breaks pre-order records.',
    status: 'published',
    category: 'Technology',
    tags: 'Smartphones,Innovation,Consumer Tech',
    is_trending_post: false,
  },
  {
    title: 'Economic Outlook: Markets Show Strong Recovery',
    content: `Financial markets are showing robust signs of recovery after a period of volatility. Economists point to several positive indicators, including job growth, increased consumer spending, and stabilizing inflation rates.

Major stock indices have reached new highs, reflecting investor confidence in the economic outlook. However, experts caution that challenges remain, including geopolitical tensions and supply chain disruptions.

Central banks are carefully monitoring the situation, balancing the need for continued support with concerns about overheating. The coming months will be crucial in determining the sustainability of this recovery.`,
    excerpt: 'Markets reach new highs as economic indicators point to sustained recovery.',
    status: 'published',
    category: 'Business',
    tags: 'Economy,Finance,Markets',
    is_trending_post: false,
  },
  {
    title: 'Space Exploration Enters New Era',
    content: `Private companies are leading a new era of space exploration, with multiple successful missions to orbit and ambitious plans for lunar and Mars exploration. This represents a fundamental shift in how humanity approaches space travel.

Recent successes include reusable rocket technology that significantly reduces the cost of space access. This innovation is making space more accessible and opening up new possibilities for scientific research and commercial ventures.

The next decade promises even more exciting developments, including potential human missions to Mars and the establishment of permanent lunar bases. Space exploration is no longer the exclusive domain of government agencies.`,
    excerpt: 'Private space companies usher in new era with successful missions and ambitious plans.',
    status: 'published',
    category: 'Science',
    tags: 'Space,Innovation,Science',
    is_trending_post: true,
  },
  {
    title: 'Healthcare Innovation: New Treatment Shows Promise',
    content: `Researchers have announced promising results from clinical trials of a new treatment for a previously difficult-to-treat condition. The breakthrough could benefit millions of patients worldwide.

The treatment uses cutting-edge technology to target the disease at a molecular level, showing significantly better results than existing therapies. Early trials indicate minimal side effects and high efficacy rates.

While regulatory approval is still pending, medical professionals are optimistic about the treatment's potential. This represents years of research and could mark a major advancement in medical science.`,
    excerpt: 'Clinical trials show promising results for breakthrough medical treatment.',
    status: 'published',
    category: 'Health',
    tags: 'Healthcare,Medicine,Research',
    is_trending_post: false,
  },
  {
    title: 'Education Technology Transforms Learning',
    content: `The education sector is undergoing a digital transformation, with new technologies enabling personalized learning experiences and improved outcomes. Schools and universities are embracing these innovations at an unprecedented pace.

Interactive platforms, AI tutors, and virtual reality classrooms are becoming increasingly common. These tools are helping educators reach students more effectively and cater to different learning styles.

The impact extends beyond traditional education, with professional development and lifelong learning benefiting from these technological advances. The future of education looks dramatically different from its past.`,
    excerpt: 'Digital innovation transforms education with personalized learning and new teaching methods.',
    status: 'published',
    category: 'Education',
    tags: 'Education,Technology,Innovation',
    is_trending_post: false,
  },
  {
    title: 'Draft: Upcoming Conference Coverage',
    content: `This is a draft post for the upcoming technology conference. Will be published after the event.`,
    excerpt: 'Draft post for conference coverage.',
    status: 'draft',
    category: 'Technology',
    tags: 'Conference,Events',
    is_trending_post: false,
  },
]

// Sample pages
const samplePages = [
  {
    title: 'About Us',
    content: `Welcome to NewsCMS - your modern, WordPress-like publishing platform built with Next.js and Supabase.

## Our Mission

We believe in democratizing content creation and providing powerful tools for publishers of all sizes. NewsCMS combines the ease of use of traditional CMSs with the power and flexibility of modern web technologies.

## Features

- **Easy Content Management**: Intuitive interface for creating and managing posts
- **Trending Topics**: AI-powered trending topic detection
- **SEO Optimized**: Built-in SEO features to help your content rank
- **Fast & Secure**: Built on Next.js for optimal performance and security
- **Scalable**: Powered by Supabase for reliable, scalable database management

## Get Started

Ready to start publishing? Check out our documentation or contact our support team.`,
    status: 'published',
  },
  {
    title: 'Privacy Policy',
    content: `# Privacy Policy

Last updated: ${new Date().toLocaleDateString()}

## Information We Collect

We are committed to protecting your privacy. This policy outlines what information we collect and how we use it.

## Data Usage

Your data is used solely to provide and improve our services. We never sell your personal information to third parties.

## Your Rights

You have the right to access, modify, or delete your personal information at any time.

## Contact

For privacy concerns, please contact us at privacy@newscms.com`,
    status: 'published',
  },
  {
    title: 'Contact',
    content: `# Get in Touch

We'd love to hear from you! Whether you have questions, feedback, or need support, we're here to help.

## Contact Information

**Email**: support@newscms.com  
**Twitter**: @NewsCMS  
**GitHub**: github.com/newscms

## Support Hours

Monday - Friday: 9:00 AM - 5:00 PM EST  
Weekend: Limited support available

## Office Location

123 Publishing Street  
Media City, MC 12345  
United States`,
    status: 'published',
  },
]

async function login() {
  log('\n🔐 Logging in as admin...', colors.cyan)
  const result = await makeRequest('/api/auth/login', {
    method: 'POST',
    body: JSON.stringify({
      email: 'admin@example.com',
      password: 'admin123',
    }),
  })
  
  if (result.status === 200) {
    log('✓ Login successful', colors.green)
    return true
  } else {
    log('✗ Login failed', colors.red)
    return false
  }
}

async function createPosts() {
  log('\n📝 Creating sample posts...', colors.cyan)
  let created = 0
  
  for (const post of samplePosts) {
    const result = await makeRequest('/api/posts', {
      method: 'POST',
      body: JSON.stringify(post),
    })
    
    if (result.status === 200 || result.status === 201) {
      created++
      log(`  ✓ Created: ${post.title}`, colors.green)
    } else {
      log(`  ✗ Failed: ${post.title}`, colors.red)
    }
  }
  
  log(`\n✓ Created ${created}/${samplePosts.length} posts`, colors.green)
}

async function createPages() {
  log('\n📄 Creating sample pages...', colors.cyan)
  let created = 0
  
  for (const page of samplePages) {
    const result = await makeRequest('/api/pages', {
      method: 'POST',
      body: JSON.stringify(page),
    })
    
    if (result.status === 200 || result.status === 201) {
      created++
      log(`  ✓ Created: ${page.title}`, colors.green)
    } else {
      log(`  ✗ Failed: ${page.title}`, colors.red)
    }
  }
  
  log(`\n✓ Created ${created}/${samplePages.length} pages`, colors.green)
}

async function updateSettings() {
  log('\n⚙️  Updating site settings...', colors.cyan)
  
  const settings = {
    site_name: 'NewsCMS Demo',
    site_description: 'A modern news publishing platform - Demo Site',
    site_url: 'http://localhost:3000',
  }
  
  const result = await makeRequest('/api/settings', {
    method: 'PUT',
    body: JSON.stringify(settings),
  })
  
  if (result.status === 200) {
    log('✓ Settings updated', colors.green)
  } else {
    log('✗ Failed to update settings', colors.red)
  }
}

async function main() {
  log('\n' + '█'.repeat(60), colors.bright + colors.blue)
  log('  NewsCMS - Demo Data Generator  ', colors.bright + colors.blue)
  log('█'.repeat(60) + '\n', colors.bright + colors.blue)
  
  log(`API Base: ${API_BASE}`, colors.cyan)
  log('This will populate your database with sample content.\n', colors.yellow)
  
  const loggedIn = await login()
  
  if (!loggedIn) {
    log('\n✗ Cannot proceed without authentication', colors.red)
    log('Make sure Supabase is configured and the database is set up.', colors.yellow)
    process.exit(1)
  }
  
  await createPosts()
  await createPages()
  await updateSettings()
  
  log('\n' + '═'.repeat(60), colors.green)
  log('🎉 Demo data creation complete!', colors.bright + colors.green)
  log('═'.repeat(60), colors.green)
  
  log('\n📊 Summary:', colors.cyan)
  log(`  • ${samplePosts.length} sample posts created`, colors.white)
  log(`  • ${samplePages.length} sample pages created`, colors.white)
  log('  • Site settings updated', colors.white)
  
  log('\n🌐 Next steps:', colors.cyan)
  log('  1. Visit http://localhost:3000 to see your site', colors.white)
  log('  2. Login at http://localhost:3000/login', colors.white)
  log('  3. Access admin panel at http://localhost:3000/admin\n', colors.white)
}

main().catch(error => {
  log(`\n✗ Error: ${error.message}`, colors.red)
  console.error(error)
  process.exit(1)
})
