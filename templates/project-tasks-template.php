<?php
/*
Template Name: Project Tasks
*/
get_header();

// Get the project ID from the URL query parameter
$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;
$project_title = get_the_title($project_id);

// Query and display tasks associated with the project
$project_tasks_query = new WP_Query(array(
    'post_type' => 'tasks', // Replace with your Task CPT name
    'meta_query' => array(
        array(
            'key' => 'project', // Replace with your ACF field name or user field name
            'value' => $project_id,
            'compare' => '=',
        ),
    ),
));

?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <!-- Display Project Tasks -->
        <div class="project-tasks">
            <h2>Tasks for This Project : </h2>
            <?php if ($project_tasks_query->have_posts()) : ?>
                <ul style="font-size: larger;">
                    <?php while ($project_tasks_query->have_posts()) : $project_tasks_query->the_post(); ?>
                        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    <?php endwhile; ?>
                </ul>
            <?php else : ?>
                <p>No tasks found for this project.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php get_footer(); ?>