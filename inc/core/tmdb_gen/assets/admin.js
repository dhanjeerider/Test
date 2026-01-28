jQuery(document).ready(function($){
    let selectedItems = [];
    let currentType = 'movie';
    let currentPage = 1;
    let currentSearch = '';
    let isLoadingMore = false;
    let maxRetries = 3;
    
    // Content type change
    $('#tmdb-content-type').on('change', function(){
        currentType = $(this).val();
        $('#tmdb-results-grid').empty();
        selectedItems = [];
        currentPage = 1;
        updateSelectedCount();
    });
    
    // Search button
    $('#tmdb-search-btn').on('click', function(){
        let query = $('#tmdb-search-input').val().trim();
        
        if(!query){
            alert('Please enter a search term');
            return;
        }
        
        searchContent(query);
    });
    
    // Enter key search
    $('#tmdb-search-input').on('keypress', function(e){
        if(e.which == 13){
            $('#tmdb-search-btn').click();
        }
    });
    
    // Popular button
    $('#tmdb-popular-btn').on('click', function(){
        getPopular();
    });
    
    // Genre filter
    $('#tmdb-genre-filter').on('change', function(){
        if(currentSearch){
            searchContent(currentSearch);
        }
    });
    
    // Year filter
    $('#tmdb-year-filter').on('change', function(){
        if(currentSearch){
            searchContent(currentSearch);
        }
    });
    
    // Load more button
    $('#load-more-results').on('click', function(){
        if(isLoadingMore) return;
        currentPage++;
        if(currentSearch){
            searchContent(currentSearch, true);
        } else {
            getPopular(true);
        }
    });
    
    // Select all
    $('#select-all').on('click', function(){
        $('.tmdb-item').addClass('selected');
        $('.tmdb-checkbox').prop('checked', true);
        selectedItems = $('.tmdb-item').map(function(){
            return $(this).data('id');
        }).get();
        updateSelectedCount();
    });
    
    // Deselect all
    $('#deselect-all').on('click', function(){
        $('.tmdb-item').removeClass('selected');
        $('.tmdb-checkbox').prop('checked', false);
        selectedItems = [];
        updateSelectedCount();
    });
    
    // Item click
    $(document).on('click', '.tmdb-item', function(e){
        if($(e.target).hasClass('tmdb-checkbox')){
            return;
        }
        
        let checkbox = $(this).find('.tmdb-checkbox');
        checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
    });
    
    // Checkbox change
    $(document).on('change', '.tmdb-checkbox', function(){
        let item = $(this).closest('.tmdb-item');
        let id = item.data('id');
        
        if($(this).is(':checked')){
            item.addClass('selected');
            if(!selectedItems.includes(id)){
                selectedItems.push(id);
            }
        } else {
            item.removeClass('selected');
            selectedItems = selectedItems.filter(i => i !== id);
        }
        
        updateSelectedCount();
    });
    
    // Import selected
    $('#import-selected').on('click', function(){
        if(selectedItems.length === 0){
            alert('Please select at least one item');
            return;
        }
        
        if(!confirm(`Import ${selectedItems.length} ${currentType}(s)?`)){
            return;
        }
        
        startBulkImport();
    });
    
    // Search content
    function searchContent(query, loadMore = false){
        currentSearch = query;
        isLoadingMore = loadMore;
        
        if(!loadMore){
            currentPage = 1;
            $('#tmdb-results-grid').html('<div class="loading">Searching...</div>');
        } else {
            $('#load-more-results').text('Loading...');
        }
        
        let genre = $('#tmdb-genre-filter').val();
        let year = $('#tmdb-year-filter').val();
        
        $.post(tmdbgen.ajax_url, {
            action: 'tmdbgen_search',
            nonce: tmdbgen.nonce,
            query: query,
            type: currentType,
            page: currentPage,
            genre: genre,
            year: year
        }, function(response){
            if(response.success){
                displayResults(response.data, loadMore);
                
                // Show/hide load more button
                if(response.data.length >= 20){
                    $('#load-more-results').show();
                } else {
                    $('#load-more-results').hide();
                }
            } else {
                if(!loadMore){
                    $('#tmdb-results-grid').html('<div class="error">Search failed</div>');
                }
            }
            isLoadingMore = false;
            $('#load-more-results').text('Load More Results');
        });
    }
    
    // Get popular
    function getPopular(loadMore = false){
        currentSearch = '';
        isLoadingMore = loadMore;
        
        if(!loadMore){
            currentPage = 1;
            $('#tmdb-results-grid').html('<div class="loading">Loading popular...</div>');
        } else {
            $('#load-more-results').text('Loading...');
        }
        
        $.post(tmdbgen.ajax_url, {
            action: 'tmdbgen_get_popular',
            nonce: tmdbgen.nonce,
            type: currentType,
            page: currentPage
        }, function(response){
            if(response.success){
                displayResults(response.data, loadMore);
                $('#load-more-results').show();
            } else {
                if(!loadMore){
                    $('#tmdb-results-grid').html('<div class="error">Failed to load</div>');
                }
            }
            isLoadingMore = false;
            $('#load-more-results').text('Load More Results');
        });
    }
    
    // Display results
    function displayResults(data, append = false){
        if(!append){
            $('#tmdb-results-grid').empty();
            $('#result-count').text(`(${data.length} results)`);
        } else {
            let currentCount = $('.tmdb-item').length;
            $('#result-count').text(`(${currentCount + data.length} results)`);
        }
        
        if(data.length === 0 && !append){
            $('#tmdb-results-grid').html('<div class="no-results">No results found</div>');
            $('#load-more-results').hide();
            return;
        }
        
        data.forEach(function(item){
            let poster = item.poster_path 
                ? `https://image.tmdb.org/t/p/w200${item.poster_path}`
                : 'https://via.placeholder.com/150x220?text=No+Image';
            
            let title = item.title || item.name;
            let date = item.release_date || item.first_air_date || 'N/A';
            let year = date.split('-')[0];
            
            let html = `
                <div class="tmdb-item" data-id="${item.id}">
                    <input type="checkbox" class="tmdb-checkbox">
                    <img src="${poster}" alt="${title}">
                    <div class="tmdb-item-info">
                        <div class="tmdb-item-title" title="${title}">${title}</div>
                        <div class="tmdb-item-meta">
                            ${year} • ⭐ ${item.vote_average || 'N/A'}
                        </div>
                    </div>
                </div>
            `;
            
            $('#tmdb-results-grid').append(html);
        });
    }
    
    // Update selected count
    function updateSelectedCount(){
        $('#selected-count').text(selectedItems.length);
    }
    
    // Start bulk import
    function startBulkImport(){
        $('.tmdbgen-progress').show();
        $('#import-grid').empty();
        
        // Hide results
        $('.tmdbgen-results').hide();
        
        // Create grid items
        let items = [];
        $('.tmdb-item.selected').each(function(){
            let id = $(this).data('id');
            let title = $(this).find('.tmdb-item-title').text();
            let poster = $(this).find('img').attr('src');
            
            items.push({id, title, poster});
            
            let html = `
                <div class="import-item" data-id="${id}">
                    <img src="${poster}" alt="${title}">
                    <div class="import-status">Waiting...</div>
                    <div class="import-item-info">
                        <div class="import-item-title">${title}</div>
                    </div>
                </div>
            `;
            
            $('#import-grid').append(html);
        });
        
        // Process import with 1 second delay
        processImportQueue(items, 0);
    }
    
    // Process import queue
    function processImportQueue(items, index, retryCount = 0){
        if(index >= items.length){
            setTimeout(function(){
                let successCount = $('.import-item.success').length;
                let failedCount = $('.import-item.error').length;
                alert(`Import completed!\n✅ Success: ${successCount}\n❌ Failed: ${failedCount}`);
                location.reload();
            }, 2000);
            return;
        }
        
        let item = items[index];
        let progress = Math.round(((index + 1) / items.length) * 100);
        
        // Update progress bar
        $('.progress-fill').css('width', progress + '%');
        $('.progress-text').text(progress + '%');
        
        // Update item status
        let statusText = retryCount > 0 ? `Retry ${retryCount}/${maxRetries}...` : 'Importing...';
        $(`[data-id="${item.id}"]`).addClass('importing').find('.import-status').text(statusText);
        
        // Import via AJAX
        $.post(tmdbgen.ajax_url, {
            action: 'tmdbgen_import_single',
            nonce: tmdbgen.nonce,
            tmdb_id: item.id,
            type: currentType
        }, function(response){
            if(response.success){
                $(`[data-id="${item.id}"]`)
                    .removeClass('importing')
                    .addClass('success')
                    .find('.import-status')
                    .html('✓ Success');
                
                // Move to next item
                setTimeout(function(){
                    processImportQueue(items, index + 1, 0);
                }, 1000);
            } else {
                // Retry logic
                if(retryCount < maxRetries){
                    setTimeout(function(){
                        processImportQueue(items, index, retryCount + 1);
                    }, 2000);
                } else {
                    $(`[data-id="${item.id}"]`)
                        .removeClass('importing')
                        .addClass('error')
                        .find('.import-status')
                        .html('✗ Failed');
                    
                    setTimeout(function(){
                        processImportQueue(items, index + 1, 0);
                    }, 1000);
                }
            }
        }).fail(function(){
            // Retry on network error
            if(retryCount < maxRetries){
                setTimeout(function(){
                    processImportQueue(items, index, retryCount + 1);
                }, 2000);
            } else {
                $(`[data-id="${item.id}"]`)
                    .removeClass('importing')
                    .addClass('error')
                    .find('.import-status')
                    .html('✗ Network Error');
                
                setTimeout(function(){
                    processImportQueue(items, index + 1, 0);
                }, 1000);
            }
        });
    }
});
