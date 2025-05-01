<?php
require 'db_connect.php';
$tasks = $pdo->query('SELECT * FROM tasks ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To Do List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">TO DO LIST</h1>

        <form action="add_task.php" method="POST" class="flex gap-2 mb-6">
            <input 
                type="text" 
                name="task_name" 
                placeholder="Add new task ..." 
                required 
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Submit</button>
        </form>

        <ul class="space-y-3">
            <?php foreach ($tasks as $task): ?>
                <li class="flex items-center justify-between bg-gray-50 px-4 py-2 rounded-lg shadow-sm">
                    <span class="<?php echo $task['is_completed'] == 1 ? 'line-through text-gray-400' : 'text-gray-800'; ?>">
                        <?php echo htmlspecialchars($task['task_name']); ?>
                    </span>
                    <div class="flex gap-2">
                        <a 
                            href="toggle_task.php?id=<?php echo $task['id']; ?>" 
                            class="text-sm text-white px-3 py-1 rounded-lg 
                            <?php echo $task['is_completed'] == 1 ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600'; ?>">
                            <?php echo $task['is_completed'] == 1 ? 'NOT DONE' : 'DONE'; ?>
                        </a>
                        <a 
                            href="delete_task.php?id=<?php echo $task['id']; ?>" 
                            onclick="return confirm('ARE YOU SURE ? ...')" 
                            class="text-sm bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg">
                            DELETE
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
