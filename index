<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collaborative Drawing</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100%;
        }

        #drawingCanvas {
            display: block;
            background-color: #fff;
            width: 100vw;
            height: 100vh;
        }

        #buttons {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }

        button {
            padding: 10px 20px;
            margin-right: 5px;
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        button.eraser {
            background-color: blue;
        }

        .slide {
            position: fixed;
            left: 0;
            top: 40%;
            z-index: 9999;
            opacity: 0.4;
        }
    </style>
</head>
<body>
    <div id="buttons">
        <button id="clearButton">Remove All</button>
        <button id="eraserButton" class="eraser">Eraser</button>
    </div>
    <canvas id="drawingCanvas"></canvas>
    <img id="slid" class="slide" src="sidebar.png" alt="server_error" width="30">

    <script>
        const canvas = document.getElementById('drawingCanvas');
        const context = canvas.getContext('2d');
        const clearButton = document.getElementById('clearButton');
        const eraserButton = document.getElementById('eraserButton');
        const slid = document.getElementById('slid');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        let drawing = false;
        let erasing = false;
        let drawBuffer = [];

        function getCoordinates(e) {
            if (e.touches) {
                return { x: e.touches[0].clientX, y: e.touches[0].clientY };
            }
            return { x: e.clientX, y: e.clientY };
        }

        function startPosition(e) {
            drawing = true;
            draw(e);
        }

        function endPosition() {
            drawing = false;
            context.beginPath();
            drawBuffer.push({ type: 'end' });

            // Send the buffer when drawing ends
            sendDrawData(drawBuffer);
            drawBuffer = [];
        }

        function draw(e) {
            if (!drawing) return;
            const { x, y } = getCoordinates(e);
            context.lineWidth = 5;
            context.lineCap = 'round';
            context.strokeStyle = erasing ? '#fff' : '#000';

            context.lineTo(x, y);
            context.stroke();
            context.beginPath();
            context.moveTo(x, y);

            drawBuffer.push({
                x: x,
                y: y,
                type: 'draw',
                erase: erasing
            });
        }

        function sendDrawData(buffer) {
            if (buffer.length === 0) return;
            fetch('time1.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ draw: buffer })
            });
        }

        function clearDrawing() {
            context.clearRect(0, 0, canvas.width, canvas.height);
            fetch('time1.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ clear: true })
            });
        }

        async function getDrawData() {
            const response = await fetch('time1.php');
            const drawData = await response.json();

            context.clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas before redrawing

            drawData.forEach(data => {
                context.strokeStyle = data.erase ? '#fff' : '#000';
                if (data.type === 'draw') {
                    context.lineTo(data.x, data.y);
                    context.stroke();
                    context.beginPath();
                    context.moveTo(data.x, data.y);
                } else if (data.type === 'end') {
                    context.beginPath();
                }
            });

            setTimeout(getDrawData, 50);  // Polling every 5 seconds
        }

        canvas.addEventListener('mousedown', startPosition);
        canvas.addEventListener('mouseup', endPosition);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('touchstart', startPosition);
        canvas.addEventListener('touchend', endPosition);
        canvas.addEventListener('touchmove', draw);
        clearButton.addEventListener('click', clearDrawing);
        eraserButton.addEventListener('click', () => {
            erasing = !erasing;
            eraserButton.style.backgroundColor = erasing ? 'lightblue' : 'blue';
        });

        slid.addEventListener("click", (event) => {
            event.preventDefault(); // Prevents the default action of the link
            window.open(`http://chat1.free.nf/?i=1`, '_blank');
        });

        // Send draw data every second if there are any points
        setInterval(() => {
            sendDrawData(drawBuffer);
            drawBuffer = [];
        }, 50);

        getDrawData();
    </script>
</body>
</html>
