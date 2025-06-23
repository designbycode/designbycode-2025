<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Markdown Table Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Assuming Tailwind is set up via Vite --}}
</head>
<body class="p-8 bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

<div class="container mx-auto prose dark:prose-invert max-w-4xl">
    <h1>Markdown Table Styling Test</h1>

    <p>This table tests the custom Tailwind CSS styling applied via CommonMark renderers.</p>

@markdown
| Product Name          | Color  | Category    | Price  | In Stock |
|-----------------------|--------|-------------|--------|----------|
| Apple MacBook Pro 17" | Silver | Laptop      | $2999  | Yes      |
| Microsoft Surface Pro | White  | Laptop PC   | $1999  | No       |
| Magic Mouse 2         | Black  | Accessories | $99    | Yes      |
| USB-C Hub             | Gray   | Accessories | $39.99 | Yes      |
| External SSD 1TB      | Black  | Storage     | $129   | No       |
@endmarkdown

    <h2>Another Table</h2>

@markdown
| Task                | Command                    | Notes                          |
|---------------------|----------------------------|--------------------------------|
| Initialize Repo     | `git init`                 | Starts a new git repository    |
| Stage All Changes   | `git add .`                | Stages all modified files      |
| Commit Changes      | `git commit -m "Message"`  | Commits staged files           |
@endmarkdown

</div>

</body>
</html>
