<div class="wrap tmdbgen-wrap">
    <h1>🎬 TMDB Bulk Importer</h1>
    
    <div class="tmdbgen-container">
        <!-- Search Section -->
        <div class="tmdbgen-search-box">
            <h2>Search & Import Content</h2>
            
            <div class="search-controls">
                <select id="tmdb-content-type">
                    <option value="movie">Movies</option>
                    <option value="tv">TV Shows</option>
                </select>
                
                <input type="text" id="tmdb-search-input" placeholder="Search TMDB...">
                
                <button class="button button-primary" id="tmdb-search-btn">Search</button>
                <button class="button" id="tmdb-popular-btn">Get Popular</button>
            </div>
            
            <div class="filter-controls">
                <label>
                    <span>Genre:</span>
                    <select id="tmdb-genre-filter">
                        <option value="">All Genres</option>
                        <option value="28">Action</option>
                        <option value="12">Adventure</option>
                        <option value="16">Animation</option>
                        <option value="35">Comedy</option>
                        <option value="80">Crime</option>
                        <option value="99">Documentary</option>
                        <option value="18">Drama</option>
                        <option value="10751">Family</option>
                        <option value="14">Fantasy</option>
                        <option value="36">History</option>
                        <option value="27">Horror</option>
                        <option value="10402">Music</option>
                        <option value="9648">Mystery</option>
                        <option value="10749">Romance</option>
                        <option value="878">Science Fiction</option>
                        <option value="10770">TV Movie</option>
                        <option value="53">Thriller</option>
                        <option value="10752">War</option>
                        <option value="37">Western</option>
                    </select>
                </label>
                
                <label>
                    <span>Year:</span>
                    <select id="tmdb-year-filter">
                        <option value="">All Years</option>
                        <?php for($y = date('Y'); $y >= 1990; $y--): ?>
                            <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </label>
            </div>
        </div>
        
        <!-- Results Grid -->
        <div class="tmdbgen-results">
            <h3>Search Results <span id="result-count"></span></h3>
            <div class="select-actions">
                <button class="button" id="select-all">Select All</button>
                <button class="button" id="deselect-all">Deselect All</button>
                <button class="button button-primary button-large" id="import-selected">
                    Import Selected (<span id="selected-count">0</span>)
                </button>
            </div>
            
            <div id="tmdb-results-grid"></div>
            
            <div class="load-more-wrapper">
                <button class="button button-large" id="load-more-results" style="display:none;">
                    Load More Results
                </button>
            </div>
        </div>
        
        <!-- Import Progress -->
        <div class="tmdbgen-progress" style="display:none;">
            <h3>Import Progress</h3>
            <div class="progress-bar-wrapper">
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <span class="progress-text">0%</span>
            </div>
            <div id="import-grid"></div>
        </div>
    </div>
</div>

<style>
.tmdbgen-wrap {
    background: #f5f5f5;
    padding: 20px;
    margin: 20px 0 0 -20px;
}

.tmdbgen-container {
    max-width: 1400px;
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.tmdbgen-search-box {
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 6px;
}

.search-controls {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-bottom: 15px;
}

.filter-controls {
    display: flex;
    gap: 20px;
    align-items: center;
    padding: 15px;
    background: white;
    border-radius: 4px;
    border: 1px solid #e0e0e0;
}

.filter-controls label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

.filter-controls select {
    padding: 6px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 13px;
}

.load-more-wrapper {
    text-align: center;
    margin: 30px 0;
}

#load-more-results {
    padding: 12px 40px;
    font-size: 14px;
    font-weight: 600;
}

#tmdb-content-type {
    padding: 8px 12px;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

#tmdb-search-input {
    flex: 1;
    padding: 10px 15px;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.select-actions {
    margin: 15px 0;
    display: flex;
    gap: 10px;
    align-items: center;
}

#selected-count {
    font-weight: bold;
    color: #0073aa;
}

#tmdb-results-grid, #import-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.tmdb-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.tmdb-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.tmdb-item.selected {
    border: 3px solid #0073aa;
}

.tmdb-item img {
    width: 100%;
    height: 220px;
    object-fit: cover;
}

.tmdb-item-info {
    padding: 10px;
}

.tmdb-item-title {
    font-size: 13px;
    font-weight: bold;
    margin-bottom: 5px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.tmdb-item-meta {
    font-size: 11px;
    color: #666;
}

.tmdb-checkbox {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 24px;
    height: 24px;
    cursor: pointer;
    z-index: 10;
}

/* Import Progress Styles */
.tmdbgen-progress {
    margin-top: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 6px;
}

.progress-bar-wrapper {
    position: relative;
    margin: 20px 0;
}

.progress-bar {
    width: 100%;
    height: 30px;
    background: #e0e0e0;
    border-radius: 15px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #0073aa, #00a0d2);
    transition: width 0.5s ease;
    width: 0%;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-weight: bold;
    color: #333;
    font-size: 14px;
}

.import-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.import-item img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    opacity: 0.7;
}

.import-status {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.7);
    color: white;
    font-weight: bold;
    font-size: 14px;
}

.import-item.importing .import-status {
    background: rgba(0, 115, 170, 0.9);
}

.import-item.success .import-status {
    background: rgba(40, 167, 69, 0.9);
}

.import-item.error .import-status {
    background: rgba(220, 53, 69, 0.9);
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.import-item.importing .import-status {
    animation: pulse 1s infinite;
}

.import-item-info {
    padding: 10px;
    text-align: center;
}

.import-item-title {
    font-size: 12px;
    font-weight: bold;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
