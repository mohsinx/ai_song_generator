// AI Song Generator JavaScript
jQuery(document).ready(function($) {
  const generateBtn = $('#generate-song');
  const songPrompt = $('#song-prompt');
  const songLength = $('#song-length');
  const songGenre = $('#song-genre');
  const loadingAnimation = $('.loading-animation');
  const resultContainer = $('.result-container');
  const songTitle = $('#song-title');
  const songLyrics = $('#song-lyrics');
  const copyBtn = $('#copy-song');
  const downloadBtn = $('#download-song');
  const generateNewBtn = $('#generate-new');
  
  // Generate Song Button Click
  generateBtn.on('click', function() {
    // Validate input
    if (songPrompt.val().trim() === '') {
      alert('Please enter a song description');
      return;
    }
    
    // Show loading animation
    loadingAnimation.removeClass('hidden');
    resultContainer.addClass('hidden');
    
    // Prepare the data to send
    const data = {
      action: 'generate_ai_song',
      prompt: songPrompt.val(),
      length: songLength.val(),
      genre: songGenre.val(),
      nonce: aiSongGenerator.nonce
    };
    
    // Send AJAX request
    $.ajax({
      url: aiSongGenerator.ajax_url,
      type: 'POST',
      data: data,
      success: function(response) {
        // Hide loading animation
        loadingAnimation.addClass('hidden');
        
        if (response.success) {
          // Display results
          songTitle.text(response.data.title);
          songLyrics.text(response.data.lyrics);
          resultContainer.removeClass('hidden');
        } else {
          alert('Error: ' + response.data.message);
        }
      },
      error: function() {
        loadingAnimation.addClass('hidden');
        alert('Something went wrong. Please try again.');
      }
    });
  });
  
  // Copy to Clipboard Button
  copyBtn.on('click', function() {
    const songText = songTitle.text() + '\n\n' + songLyrics.text();
    
    // Create temporary textarea to copy from
    const textarea = $('<textarea>');
    textarea.val(songText);
    $('body').append(textarea);
    textarea.select();
    document.execCommand('copy');
    textarea.remove();
    
    // Show copied feedback
    const originalText = copyBtn.text();
    copyBtn.text('Copied!');
    setTimeout(function() {
      copyBtn.text(originalText);
    }, 2000);
  });
  
  // Download as Text Button
  downloadBtn.on('click', function() {
    const songText = songTitle.text() + '\n\n' + songLyrics.text();
    const blob = new Blob([songText], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    
    // Create temporary link and trigger download
    const a = document.createElement('a');
    a.href = url;
    a.download = 'generated-song.txt';
    document.body.appendChild(a);
    a.click();
    
    // Clean up
    setTimeout(function() {
      document.body.removeChild(a);
      window.URL.revokeObjectURL(url);
    }, 0);
  });
  
  // Generate New Button
  generateNewBtn.on('click', function() {
    resultContainer.addClass('hidden');
    songPrompt.val('');
    songPrompt.focus();
  });
});