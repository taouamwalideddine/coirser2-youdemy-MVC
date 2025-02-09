<?php
$title = 'Youdemy - Teacher Dashboard';
ob_start();
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Teacher Dashboard</h1>
        <a href="index.php?action=add_course" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-200">Add New Course</a>
    </div>

    <!-- Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Students Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Enrolled Students</p>
                    <p class="text-2xl font-bold text-gray-800"><?= $totalStudents ?? 0 ?></p>
                </div>
            </div>
        </div>

        <!-- Total Courses Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Courses</p>
                    <p class="text-2xl font-bold text-gray-800"><?= count($teacherCourses) ?></p>
                </div>
            </div>
        </div>

        <!-- Total Views Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Views</p>
                    <p class="text-2xl font-bold text-gray-800"><?= $totalViews ?? 0 ?></p>
                </div>
            </div>
        </div>

        <!-- Average Rating Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Average Rating</p>
                    <p class="text-2xl font-bold text-gray-800"><?= number_format($averageRating ?? 0, 1) ?></p>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-bold text-gray-800 mb-6">Your Courses</h2>
    
    <?php if (empty($teacherCourses)): ?>
        <p class="text-gray-600 text-center py-8">You haven't created any courses yet.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($teacherCourses as $course): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($course->getTitle()); ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(substr($course->getDescription(), 0, 100)) . '...'; ?></p>
                        <div class="flex space-x-3">
                            <a href="index.php?action=course&id=<?php echo $course->getId(); ?>" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View Course
                            </a>
                            <button onclick="showMembers(<?php echo $course->getId(); ?>)" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                View Members
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Members Modal -->
<div id="membersModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[80vh] flex flex-col">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Course Members</h3>
            <button onclick="closeMembersModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-grow" id="membersList">
            <!-- Members will be loaded here dynamically -->
        </div>
    </div>
</div>

<script>
async function showMembers(courseId) {
    const modal = document.getElementById('membersModal');
    const membersList = document.getElementById('membersList');
    
    // Show modal and loading state
    modal.classList.remove('hidden');
    membersList.innerHTML = '<div class="flex justify-center items-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>';
    
    try {
        const response = await fetch(`index.php?action=get_course_members&course_id=${courseId}`);
        const data = await response.json();
        
        const members = data.Membres || [];
        
        let membersHtml = '<div class="space-y-4">';
        if (members.length === 0) {
            membersHtml += '<p class="text-gray-600 text-center py-4">No members enrolled in this course yet.</p>';
        } else {
            members.forEach(memberObj => {
                const member = memberObj[Object.keys(memberObj)[0]];
                membersHtml += `
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 font-semibold text-lg">
                                    ${member.name.charAt(0).toUpperCase()}
                                </span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">${member.name}</h4>
                            <p class="text-gray-600">${member.email}</p>
                        </div>
                    </div>
                `;
            });
        }
        membersHtml += '</div>';
        membersList.innerHTML = membersHtml;
        
    } catch (error) {
        console.error('Error:', error);
        membersList.innerHTML = '<p class="text-red-600 text-center py-4">Error loading members. Please try again.</p>';
    }
}

function closeMembersModal() {
    const modal = document.getElementById('membersModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('membersModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeMembersModal();
    }
});
</script>

<?php
$content = ob_get_clean();
include 'views/layout.php';
?>
