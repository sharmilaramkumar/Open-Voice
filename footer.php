<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<style>
    footer {
    padding: 1rem 2rem;
    background-color: #2c3e50;
    color: white;
    text-align: center;
    margin-top: 2rem;
}
    </style>
</main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Open Voice. All rights reserved.</p>
    </footer>
</body>
</html>