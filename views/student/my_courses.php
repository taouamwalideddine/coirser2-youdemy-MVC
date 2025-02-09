<?php
$title = 'Youdemy - My Courses';
ob_start();
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-4">My Courses</h1>
            <hr class="my-4">
        </div>
    </div>

    <?php if (empty($enrolledCourses)): ?>
        <div class="alert alert-info" role="alert">
            <h4 class="alert-heading">No Courses Yet!</h4>
            <p>You are not enrolled in any courses yet. Browse our course catalog to find interesting courses.</p>
            <hr>
            <a href="/Croiser2/" class="btn btn-primary">Browse Courses</a>
        </div>
    <?php else: ?>
<div class=" min-h-screen py-8">
    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($enrolledCourses as $course): ?>
                <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-105">
                    <div class="p-6 flex flex-col h-full">
                        <h5 class="text-xl font-bold text-white mb-3 hover:text-indigo-400 transition-colors duration-300">
                            <?php echo htmlspecialchars($course->getTitle()); ?>
                        </h5>
                        <p class="text-gray-400 flex-grow mb-4">
                            <?php echo htmlspecialchars(substr($course->getDescription(), 0, 100)) . '...'; ?>
                        </p>
                        <a href="/Croiser2/course/<?= $course->getId() ?>" 
                           class="inline-flex items-center justify-center px-4 py-2 bg-indigo-500 text-white rounded-lg 
                                  hover:bg-indigo-600 transition-colors duration-300 mt-auto font-medium">
                            View Course
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'views/layout.php';
?>

