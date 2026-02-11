-- Enable RLS on all tables
ALTER TABLE users ENABLE ROW LEVEL SECURITY;
ALTER TABLE posts ENABLE ROW LEVEL SECURITY;
ALTER TABLE pages ENABLE ROW LEVEL SECURITY;
ALTER TABLE settings ENABLE ROW LEVEL SECURITY;
ALTER TABLE menu_items ENABLE ROW LEVEL SECURITY;
ALTER TABLE import_queue ENABLE ROW LEVEL SECURITY;
ALTER TABLE trending_topics ENABLE ROW LEVEL SECURITY;
ALTER TABLE meta_tags ENABLE ROW LEVEL SECURITY;

-- Users policies (public can read usernames, admins can read all)
CREATE POLICY "Users are viewable by everyone" ON users
  FOR SELECT USING (true);

CREATE POLICY "Users can update their own profile" ON users
  FOR UPDATE USING (auth.uid() = id);

-- Posts policies (anyone can view published posts)
CREATE POLICY "Published posts are viewable by everyone" ON posts
  FOR SELECT USING (status = 'published' OR auth.uid() = author_id);

CREATE POLICY "Users can insert posts" ON posts
  FOR INSERT WITH CHECK (auth.uid() = author_id);

CREATE POLICY "Users can update their own posts" ON posts
  FOR UPDATE USING (auth.uid() = author_id);

CREATE POLICY "Users can delete their own posts" ON posts
  FOR DELETE USING (auth.uid() = author_id);

-- Pages policies (anyone can view published pages)
CREATE POLICY "Published pages are viewable by everyone" ON pages
  FOR SELECT USING (status = 'published' OR auth.uid() = author_id);

CREATE POLICY "Users can manage their own pages" ON pages
  FOR INSERT WITH CHECK (auth.uid() = author_id);

CREATE POLICY "Users can update their own pages" ON pages
  FOR UPDATE USING (auth.uid() = author_id);

-- Settings policies (admins only)
CREATE POLICY "Settings are viewable by everyone" ON settings
  FOR SELECT USING (true);

CREATE POLICY "Only admins can update settings" ON settings
  FOR UPDATE USING (
    EXISTS (SELECT 1 FROM users WHERE users.id = auth.uid() AND users.role = 'admin')
  );

-- Menu items are public
CREATE POLICY "Menu items are viewable by everyone" ON menu_items
  FOR SELECT USING (true);

CREATE POLICY "Admins can manage menu items" ON menu_items
  FOR ALL USING (
    EXISTS (SELECT 1 FROM users WHERE users.id = auth.uid() AND users.role = 'admin')
  );

-- Import queue policies
CREATE POLICY "Users can view their imports" ON import_queue
  FOR SELECT USING (true);

CREATE POLICY "Admins can manage imports" ON import_queue
  FOR ALL USING (
    EXISTS (SELECT 1 FROM users WHERE users.id = auth.uid() AND users.role = 'admin')
  );

-- Trending topics (public)
CREATE POLICY "Trending topics are viewable by everyone" ON trending_topics
  FOR SELECT USING (true);

-- Meta tags (public)
CREATE POLICY "Meta tags are viewable by everyone" ON meta_tags
  FOR SELECT USING (true);
