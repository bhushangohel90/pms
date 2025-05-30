<?php
/*
Template Name: Ticket Form 
*/

get_header(); 
?>
<section class="ticket">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-8">
                <div class="card-header text-center mb-3">
                    <h1 class="h4 mb-0">Add New Task</h1>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form id="custom-post-form" method="post">
                            <!-- Security nonce field -->
                            <input type="hidden" name="task_nonce" value="<?php echo wp_create_nonce('add_task_nonce'); ?>">
                            <input type="hidden" name="action" value="add_task">

                            <!-- Hidden field for updating existing task (if applicable) -->
                            <input type="hidden" name="task_id" value="<?php echo isset($task_id) ? $task_id : ''; ?>">

                            <!-- Task Form Fields -->
                            <div class="mb-3">
                                <label for="post-title" class="form-label">Task Name</label>
                                <input type="text" class="form-control" id="post-title" name="post_title" required>
                            </div>

                            <div class="mb-3">
                                <label for="post-editor" class="form-label">Task Description</label>
                                <div>
                                    <?php $post_content = ''; ?>
                                    <?php wp_editor($post_content, 'post_editor', [
                                        'textarea_name' => 'post_content',
                                        'media_buttons' => true,
                                        'textarea_rows' => 5,
                                    ]); ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="acf_radio_field" class="form-label">Priority</label>
                                <select id="acf_radio_field" name="acf_radio_field" class="form-select">
                                    <option value="">Select a Priority</option>
                                    <option value="high">High</option>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="allocated_time" class="form-label">Allocated Time</label>
                                <input type="text" id="allocated_time" name="allocated_time" class="form-control">
                            </div>

                            <!-- Due Date Field -->
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" id="due_date" name="due_date" class="form-control">
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="submit_task" class="btn btn-primary">Add Task</button>
                            </div>
                        </form>
                        <!-- Message container -->
                        <div id="task-message"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
