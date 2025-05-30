<?php get_header(); ?>

<style>
    /* Ensuring full compatibility with 4K resolution */
    body {
        background: #000;
        color: #fff;
        font-family: Arial, sans-serif;
        text-align: center;
        margin: 0;
        overflow: hidden;
    }

    .error-404-container {
        padding: 100px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    /* 3D and Ultra-HD Styling for 404 */
    .error-404-container h1 {
        font-size: 10em;
        font-weight: bold;
        color: #ff0000;
        text-shadow: 0px 10px 0px #900000, 
                     0px 20px 30px rgba(255, 0, 0, 0.6);
        animation: float 4s ease-in-out infinite;
    }

    .error-404-container h2 {
        font-size: 3em;
        color: black;
        text-shadow: 2px 2px 10px rgba(255, 255, 255, 0.3);
        margin-top: -10px;
    }

    .error-message {
        font-size: 1.5em;
        color: black;
        margin-top: 20px;
        max-width: 800px;
        text-shadow: 1px 1px 10px rgba(255, 255, 255, 0.2);
    }

    /* Button Styling for 4K */
    .suggestions {
        margin-top: 50px;
    }

    .suggestions a {
        display: inline-block;
        font-size: 1.5em;
        background: #ff0000;
        color: white;
        padding: 20px 40px;
        border-radius: 15px;
        text-decoration: none;
        font-weight: bold;
        box-shadow: 0px 10px 0px #900000, 
                    0px 15px 30px rgba(255, 0, 0, 0.5);
        transition: all 0.3s ease-in-out;
    }

    .suggestions a:hover {
        background: #cc0000;
        box-shadow: 0px 10px 0px #700000, 
                    0px 20px 40px rgba(255, 0, 0, 0.6);
        transform: translateY(-5px) scale(1.05);
    }

    /* Floating Animation for 3D Effect */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }

</style>

<div class="error-404-container">
    <h1>404</h1>
    <h2>Oops! Page Not Found.</h2>
    
    <div class="error-message">
        <p>This page might have been moved, deleted, or never existed in the first place.</p>
    </div>

    <div class="suggestions">
        <a href="<?php echo home_url(); ?>">Go Back to Homepage</a>
    </div>
</div>

<?php get_footer(); ?>
