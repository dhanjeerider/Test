<div class="wrap tmdbgen-wrap">
    <h1>🎮 Server Settings</h1>
    
    <div class="tmdbgen-container">
        <form method="post" id="server-settings-form">
            <?php wp_nonce_field('tmdbgen_servers', 'tmdbgen_nonce'); ?>
            
            <h2>Enable/Disable Servers</h2>
            <p>Select which servers should be added to imported content:</p>
            
            <?php
            $servers_class = new TMDBGen_Servers();
            $all_servers = $servers_class->get_default_servers();
            $enabled = $this->get_option('enabled_servers', array_keys($all_servers));
            ?>
            
            <div class="servers-grid">
                <?php foreach($all_servers as $id => $server): ?>
                    <label class="server-checkbox">
                        <input 
                            type="checkbox" 
                            name="enabled_servers[]" 
                            value="<?php echo $id; ?>"
                            <?php checked(in_array($id, $enabled)); ?>
                        >
                        <span class="server-name"><?php echo esc_html($server['name']); ?></span>
                        <?php if($server['type'] == 'both'): ?>
                            <span class="server-badge player">Player</span>
                            <span class="server-badge download">Download</span>
                        <?php else: ?>
                            <span class="server-badge <?php echo $server['type']; ?>">
                                <?php echo ucfirst($server['type']); ?>
                            </span>
                        <?php endif; ?>
                    </label>
                <?php endforeach; ?>
            </div>
            
            <div class="submit-wrapper">
                <button type="submit" class="button button-primary button-large">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.servers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 15px;
    margin: 20px 0;
}

.server-checkbox {
    display: flex;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.server-checkbox:hover {
    background: #fff;
    border-color: #0073aa;
}

.server-checkbox input[type="checkbox"] {
    margin-right: 12px;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.server-name {
    flex: 1;
    font-weight: 500;
    font-size: 14px;
}

.server-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;
    margin-left: 5px;
}

.server-badge.player {
    background: #0073aa;
    color: white;
}

.server-badge.download {
    background: #28a745;
    color: white;
}

.server-badge.both {
    background: #6f42c1;
    color: white;
}

.submit-wrapper {
    margin-top: 30px;
    text-align: center;
}
</style>

<script>
jQuery(document).ready(function($){
    $('#server-settings-form').on('submit', function(e){
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.post(ajaxurl, {
            action: 'tmdbgen_save_servers',
            ...Object.fromEntries(new URLSearchParams(formData))
        }, function(response){
            if(response.success){
                alert('Settings saved successfully!');
            } else {
                alert('Error saving settings');
            }
        });
    });
});
</script>
