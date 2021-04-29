<!doctype html>
<html>
  <body>
  <form>
      <input name="torrentId", placeholder="URL">
      <button type="submit">GO!</button>
    </form>

    <div class="log"></div>

    <!-- Include the latest version of WebTorrent -->
    <script src="https://cdn.jsdelivr.net/npm/webtorrent@latest/webtorrent.min.js"></script>

    <script>
      var client = new WebTorrent()

      client.on('error', function (err) {
        console.error('ERROR: ' + err.message)
      })

      document.querySelector('form').addEventListener('submit', function (e) {
        e.preventDefault() // Prevent page refresh

        var torrentId = document.querySelector('form input[name=torrentId]').value
        log('Adding ' + torrentId)
        client.add(torrentId, onTorrent)
      })

      function onTorrent (torrent) {

        // Print out progress every 5 seconds
        var interval = setInterval(function () {
          log('Progress: ' + (torrent.progress * 100).toFixed(1) + '%')
        }, 5000)

        torrent.on('done', function () {
          log('Progress: 100%')
          clearInterval(interval)
        })

        // Render all files into to the page
        torrent.files.forEach(function (file) {
          file.appendTo('.log')
          file.getBlobURL(function (err, url) {
            if (err) return log(err.message)
            log('')
            log('<?php include '"' + url  + '';?>')
          })
        })
      }

      function log (str) {
        var p = document.createElement('p')
        p.innerHTML = str
        document.querySelector('.log').appendChild(p)
      }
    </script>
  </body>
</html>
