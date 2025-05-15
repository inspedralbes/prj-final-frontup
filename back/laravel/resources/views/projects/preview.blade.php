<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Preview del Proyecto</title>
</head>
<body>
    <h1>{{ $project->nombre }}</h1>
    <div>
        {!! $project->html_code !!}
        <style>{{ $project->css_code }}</style>
        <script>{{ $project->js_code }}</script>
    </div>
</body>
</html>
