<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p id="modalMessage"></p>
    </div>
</div>

<?php 
    // check if there's a message and output a JS alert
    if (!empty($_SESSION['message'])) {
        $message = json_encode($_SESSION['message']);
        echo "<script type='text/javascript'>
        // Get the modal
        var modal = document.getElementById('myModal');
    
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName('close')[0];
    
        // Show the modal
        modal.style.display = 'block';
    
        // Write the message to the modal
        document.getElementById('modalMessage').innerHTML = $message;
    
        // When the user clicks on <span> (x), close the modal and reload the page
        span.onclick = function() {
            modal.style.display = 'none';
        }
        </script>";
    
        // Reset the message
        $_SESSION['message'] = "";
    }
?>