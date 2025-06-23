<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Markdown Table Test</title>
    <!-- Assuming Tailwind CSS is linked globally, e.g., in app.blade.php or via vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="p-8 bg-gray-100 dark:bg-gray-900">

<div class="prose dark:prose-invert max-w-none">
@markdown
# Markdown Table Styling Test

This is a test to see how the Tailwind CSS classes are applied to tables.

| Product name          | Color  | Category    | Price  |
|-----------------------|--------|-------------|--------|
| Apple MacBook Pro 17" | Silver | Laptop      | $2999  |
| Microsoft Surface Pro | White  | Laptop PC   | $1999  |
| Magic Mouse 2         | Black  | Accessories | $99    |

Another table:

| Header 1 | Header 2 |
|----------|----------|
| Cell 1.1 | Cell 1.2 |
| Cell 2.1 | Cell 2.2 |

@endmarkdown
</div>

</body>
</html>
