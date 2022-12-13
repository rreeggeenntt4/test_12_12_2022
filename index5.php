<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Перемещающиеся объекты</title>
    <style>
        .start {
            padding: 10px;
            font-size: 18px;
            text-align: center;
            display: block;
            margin: 5px auto;
            width: 50px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div id="app">
        <button class="start">1</button>
        <button class="start">2</button>
        <button class="start">3</button>
    </div>
    <script>
        document.addEventListener("click", function() {
            obj = document.getElementsByClassName("start");
            console.log(obj);

            Object.keys(obj);
            Object.values(obj);
            arr = Object.entries(obj);
            console.log(arr);

            temp_arr = [];
            arr.forEach(function(item, i) {
                temp_arr.push(item[1].outerHTML)
            });
            // Текущий одномерный массив
            arr = temp_arr;

            // Новый массив
            new_arr = [];

            new_arr = new_arr.concat(arr.slice(1), arr.slice(0, 1));
            console.log(new_arr);

            html = '';
            new_arr.forEach(function(item, i) {
                html = html + item;
            });

            console.log(html);
            document.getElementById("app").innerHTML = html;
        });
    </script>
</body>

</html>