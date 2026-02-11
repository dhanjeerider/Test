#!/usr/bin/env node

/**
 * Comprehensive Test Script for NewsCMS
 * Tests all features and API endpoints
 */

const API_BASE = process.env.API_BASE || 'http://localhost:3000'

// ANSI color codes
const colors = {
  reset: '\x1b[0m',
  bright: '\x1b[1m',
  green: '\x1b[32m',
  red: '\x1b[31m',
  yellow: '\x1b[33m',
  blue: '\x1b[34m',
  cyan: '\x1b[36m',
}

let testsPassed = 0
let testsFailed = 0
let authCookie = ''

function log(message, color = colors.reset) {
  console.log(`${color}${message}${colors.reset}`)
}

function logSuccess(message) {
  testsPassed++
  log(`✓ ${message}`, colors.green)
}

function logError(message) {
  testsFailed++
  log(`✗ ${message}`, colors.red)
}

function logInfo(message) {
  log(`ℹ ${message}`, colors.cyan)
}

function logSection(message) {
  console.log()
  log(`${'='.repeat(60)}`, colors.bright)
  log(message, colors.bright + colors.blue)
  log(`${'='.repeat(60)}`, colors.bright)
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
    const response = await fetch(url, {
      ...options,
      headers,
    })
    
    // Save auth cookie
    if (response.headers.get('set-cookie')) {
      authCookie = response.headers.get('set-cookie')
    }
    
    const contentType = response.headers.get('content-type')
    let data = null
    
    if (contentType && contentType.includes('application/json')) {
      data = await response.json()
    } else {
      data = await response.text()
    }
    
    return { status: response.status, data, response }
  } catch (error) {
    return { status: 0, error: error.message }
  }
}

// Test 1: Check Environment Configuration
async function testEnvironmentConfig() {
  logSection('Test 1: Environment Configuration')
  
  try {
    const hasSupabaseUrl = process.env.NEXT_PUBLIC_SUPABASE_URL && 
      process.env.NEXT_PUBLIC_SUPABASE_URL !== 'https://your-project-id.supabase.co'
    const hasSupabaseKey = process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY && 
      process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY !== 'your-anon-key-here'
    
    if (hasSupabaseUrl) {
      logSuccess('NEXT_PUBLIC_SUPABASE_URL is configured')
    } else {
      logError('NEXT_PUBLIC_SUPABASE_URL is not configured')
    }
    
    if (hasSupabaseKey) {
      logSuccess('NEXT_PUBLIC_SUPABASE_ANON_KEY is configured')
    } else {
      logError('NEXT_PUBLIC_SUPABASE_ANON_KEY is not configured')
    }
  } catch (error) {
    logError(`Environment check failed: ${error.message}`)
  }
}

// Test 2: Authentication
async function testAuthentication() {
  logSection('Test 2: Authentication')
  
  // Test login with valid credentials
  logInfo('Testing login with admin credentials...')
  const loginResult = await makeRequest('/api/auth/login', {
    method: 'POST',
    body: JSON.stringify({
      email: 'admin@example.com',
      password: 'admin123',
    }),
  })
  
  if (loginResult.status === 200 && loginResult.data.user) {
    logSuccess(`Login successful: ${loginResult.data.user.email}`)
    logInfo(`User role: ${loginResult.data.user.role}`)
  } else if (loginResult.status === 503) {
    logError('Supabase not configured - Setup required')
  } else {
    logError(`Login failed: ${JSON.stringify(loginResult.data)}`)
  }
  
  // Test login with invalid credentials
  logInfo('Testing login with invalid credentials...')
  const invalidLogin = await makeRequest('/api/auth/login', {
    method: 'POST',
    body: JSON.stringify({
      email: 'invalid@example.com',
      password: 'wrongpass',
    }),
  })
  
  if (invalidLogin.status === 401) {
    logSuccess('Invalid credentials correctly rejected')
  } else {
    logError('Invalid credentials should be rejected')
  }
}

// Test 3: Posts API
async function testPostsAPI() {
  logSection('Test 3: Posts API')
  
  // Get all posts
  logInfo('Fetching all posts...')
  const postsResult = await makeRequest('/api/posts')
  
  if (postsResult.status === 200) {
    logSuccess(`Fetched ${postsResult.data.posts?.length || 0} posts`)
  } else if (postsResult.status === 503) {
    logError('Posts API unavailable - Supabase not configured')
  } else {
    logError(`Failed to fetch posts: ${postsResult.status}`)
  }
  
  // Create a test post
  logInfo('Creating a test post...')
  const newPost = {
    title: 'Test Post ' + Date.now(),
    content: 'This is a test post created by the test script.',
    excerpt: 'Test excerpt',
    status: 'draft',
    category: 'Test',
    tags: 'test,automated',
  }
  
  const createResult = await makeRequest('/api/posts', {
    method: 'POST',
    body: JSON.stringify(newPost),
  })
  
  let createdPostId = null
  if (createResult.status === 201 || createResult.status === 200) {
    logSuccess(`Post created: ${createResult.data.post?.title}`)
    createdPostId = createResult.data.post?.id
  } else if (createResult.status === 401) {
    logError('Not authenticated - Login required to create posts')
  } else if (createResult.status === 503) {
    logError('Cannot create post - Supabase not configured')
  } else {
    logError(`Failed to create post: ${createResult.status}`)
  }
  
  // Update the post
  if (createdPostId) {
    logInfo('Updating the test post...')
    const updateResult = await makeRequest(`/api/posts/${createdPostId}`, {
      method: 'PUT',
      body: JSON.stringify({
        title: 'Updated Test Post',
        status: 'published',
      }),
    })
    
    if (updateResult.status === 200) {
      logSuccess('Post updated successfully')
    } else {
      logError(`Failed to update post: ${updateResult.status}`)
    }
    
    // Delete the post
    logInfo('Deleting the test post...')
    const deleteResult = await makeRequest(`/api/posts/${createdPostId}`, {
      method: 'DELETE',
    })
    
    if (deleteResult.status === 200) {
      logSuccess('Post deleted successfully')
    } else {
      logError(`Failed to delete post: ${deleteResult.status}`)
    }
  }
}

// Test 4: Pages API
async function testPagesAPI() {
  logSection('Test 4: Pages API')
  
  // Get all pages
  logInfo('Fetching all pages...')
  const pagesResult = await makeRequest('/api/pages')
  
  if (pagesResult.status === 200) {
    logSuccess(`Fetched ${pagesResult.data.pages?.length || 0} pages`)
  } else if (pagesResult.status === 503) {
    logError('Pages API unavailable - Supabase not configured')
  } else {
    logError(`Failed to fetch pages: ${pagesResult.status}`)
  }
  
  // Create a test page
  logInfo('Creating a test page...')
  const newPage = {
    title: 'Test Page ' + Date.now(),
    content: 'This is a test page.',
    status: 'draft',
  }
  
  const createResult = await makeRequest('/api/pages', {
    method: 'POST',
    body: JSON.stringify(newPage),
  })
  
  let createdPageId = null
  if (createResult.status === 201 || createResult.status === 200) {
    logSuccess(`Page created: ${createResult.data.page?.title}`)
    createdPageId = createResult.data.page?.id
  } else if (createResult.status === 401) {
    logError('Not authenticated - Login required to create pages')
  } else {
    logError(`Failed to create page: ${createResult.status}`)
  }
  
  // Clean up
  if (createdPageId) {
    logInfo('Deleting test page...')
    await makeRequest(`/api/pages/${createdPageId}`, { method: 'DELETE' })
  }
}

// Test 5: Settings API
async function testSettingsAPI() {
  logSection('Test 5: Settings API')
  
  logInfo('Fetching site settings...')
  const settingsResult = await makeRequest('/api/settings')
  
  if (settingsResult.status === 200 && settingsResult.data.settings) {
    logSuccess('Settings fetched successfully')
    logInfo(`Site name: ${settingsResult.data.settings.site_name}`)
    logInfo(`Site description: ${settingsResult.data.settings.site_description}`)
  } else if (settingsResult.status === 503) {
    logError('Settings API unavailable - Supabase not configured')
  } else {
    logError(`Failed to fetch settings: ${settingsResult.status}`)
  }
}

// Test 6: Trending API
async function testTrendingAPI() {
  logSection('Test 6: Trending API')
  
  logInfo('Fetching trending topics...')
  const trendingResult = await makeRequest('/api/trending')
  
  if (trendingResult.status === 200) {
    logSuccess(`Fetched ${trendingResult.data.topics?.length || 0} trending topics`)
  } else if (trendingResult.status === 503) {
    logError('Trending API unavailable - Supabase not configured')
  } else {
    logError(`Failed to fetch trending topics: ${trendingResult.status}`)
  }
}

// Test 7: Menu API
async function testMenuAPI() {
  logSection('Test 7: Menu API')
  
  logInfo('Fetching menu items...')
  const menuResult = await makeRequest('/api/menu')
  
  if (menuResult.status === 200) {
    logSuccess(`Fetched ${menuResult.data.items?.length || 0} menu items`)
  } else if (menuResult.status === 503) {
    logError('Menu API unavailable - Supabase not configured')
  } else {
    logError(`Failed to fetch menu: ${menuResult.status}`)
  }
}

// Test 8: Import API
async function testImportAPI() {
  logSection('Test 8: Bulk Import API')
  
  logInfo('Testing bulk import...')
  const importData = {
    posts: [
      {
        title: 'Imported Post 1',
        content: 'Content 1',
        excerpt: 'Excerpt 1',
      },
      {
        title: 'Imported Post 2',
        content: 'Content 2',
        excerpt: 'Excerpt 2',
      },
    ],
  }
  
  const importResult = await makeRequest('/api/import', {
    method: 'POST',
    body: JSON.stringify(importData),
  })
  
  if (importResult.status === 200) {
    logSuccess(`Bulk import queued: ${importResult.data.queued} posts`)
  } else if (importResult.status === 401) {
    logError('Not authenticated - Login required for import')
  } else if (importResult.status === 503) {
    logError('Import API unavailable - Supabase not configured')
  } else {
    logError(`Failed to import: ${importResult.status}`)
  }
}

// Run all tests
async function runAllTests() {
  log('\n' + '█'.repeat(60), colors.bright + colors.cyan)
  log('  NewsCMS - Comprehensive Feature Test Suite  ', colors.bright + colors.cyan)
  log('█'.repeat(60) + '\n', colors.bright + colors.cyan)
  
  logInfo(`Testing API at: ${API_BASE}`)
  logInfo('Starting test suite...\n')
  
  await testEnvironmentConfig()
  await testAuthentication()
  await testPostsAPI()
  await testPagesAPI()
  await testSettingsAPI()
  await testTrendingAPI()
  await testMenuAPI()
  await testImportAPI()
  
  // Summary
  logSection('Test Summary')
  log(`Total tests passed: ${testsPassed}`, colors.green)
  log(`Total tests failed: ${testsFailed}`, colors.red)
  
  const total = testsPassed + testsFailed
  const percentage = total > 0 ? ((testsPassed / total) * 100).toFixed(1) : 0
  
  log(`\nSuccess rate: ${percentage}%`, percentage >= 80 ? colors.green : colors.yellow)
  
  if (testsFailed === 0) {
    log('\n🎉 All tests passed!', colors.bright + colors.green)
  } else {
    log('\n⚠️  Some tests failed. Check configuration and database setup.', colors.yellow)
  }
  
  console.log()
  process.exit(testsFailed > 0 ? 1 : 0)
}

// Run the tests
runAllTests().catch(error => {
  logError(`Test suite error: ${error.message}`)
  console.error(error)
  process.exit(1)
})
