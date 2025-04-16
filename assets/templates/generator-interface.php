<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>
<!-- AI Song Generator Main Interface -->
<div class="ai-song-generator-container">
  <h2>AI Song Generator</h2>
  <div class="song-form">
    <div class="form-group">
      <label for="song-prompt">What kind of song would you like?</label>
      <textarea id="song-prompt" placeholder="Describe the song you want (genre, mood, topic, etc.)"></textarea>
    </div>
    <div class="form-options">
      <div class="form-group">
        <label for="song-length">Song Length:</label>
        <select id="song-length">
          <option value="short">Short (1 verse, 1 chorus)</option>
          <option value="medium" selected>Medium (2 verses, chorus, bridge)</option>
          <option value="long">Long (3+ verses, multiple choruses)</option>
        </select>
      </div>
      <div class="form-group">
        <label for="song-genre">Preferred Genre:</label>
        <select id="song-genre">
          <option value="any">Any Genre</option>
          <option value="pop">Pop</option>
          <option value="rock">Rock</option>
          <option value="hip-hop">Hip-Hop</option>
          <option value="country">Country</option>
          <option value="jazz">Jazz</option>
          <option value="folk">Folk</option>
          <option value="electronic">Electronic</option>
        </select>
      </div>
    </div>
    <button id="generate-song" class="generate-button">Generate Song</button>
  </div>
  
  <div class="loading-animation hidden">
    <div class="spinner"></div>
    <p>Creating your masterpiece...</p>
  </div>
  
  <div class="result-container hidden">
    <h3>Your Generated Song</h3>
    <div class="song-content">
      <div id="song-title"></div>
      <div id="song-lyrics"></div>
    </div>
    <div class="action-buttons">
      <button id="copy-song" class="action-button">Copy to Clipboard</button>
      <button id="download-song" class="action-button">Download as Text</button>
      <button id="generate-new" class="action-button">Generate Another</button>
    </div>
  </div>
</div>