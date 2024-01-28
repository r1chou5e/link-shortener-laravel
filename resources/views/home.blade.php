<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .error-container {
            color: red;
        }

        #shortLinkResult {
            margin-top: 20px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>URL Shortener</h1>
        @if(session('error'))
        <div class="error-container">
            {{ session('error') }}
        </div>
        @endif
        <form action="{{ route('generate') }}" method="post">
            @csrf
            <label for="originalUrl">Original URL:</label>
            <input type="text" name="original_url" id="originalUrl" placeholder="Enter your URL...">
            <button type="submit">Generate Short Link</button>
        </form>
        @if(isset($newLink))
        <div id="shortLinkResult">
            Short URL: <a href="{{ 'http://'.$newLink->short_url }}" target="_blank">{{ $newLink->short_url }}</a>
        </div>
        @endif

    </div>
</body>

</html>