<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="favicon.png">
    <title>𝙋𝙊𝙈𝙊𝘿𝙊𝙍𝙊 🍅</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="style.css">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap" rel="stylesheet">
</head>

<body>
    <section>
        <div class="p-3 d-flex justify-content-between align-content-center">
            <div class="text-center">
                <div class="date">
                    <?php echo date("D, d/m/Y") ?>
                </div>
                <div class="time" id="time">
                    10:25:55
                </div>
            </div>
            <div class="settings">
                <i class="bi bi-gear"></i>
            </div>
        </div>
    </section>

    <section class="d-flex justify-content-center align-content-center text-center">
        <div>
            <div class="timer">25:00</div>
            <div>
                <img src="pomodoro.png" alt="pomodoro" class="pomodoro">
            </div>
            <div class="timer-progress">
                <ul>
                    <li class="inprogress">
                        <i class="bi bi-1-circle-fill"></i>
                    </li>
                    <li class="inprogress">
                        <i class="bi bi-arrow-right-short"></i>
                    </li>
                    <li class="inprogress">
                        <i class="bi bi-2-circle-fill"></i>
                    </li>
                    <li class="inprogress">
                        <i class="bi bi-arrow-right-short"></i>
                    </li>
                    <li class="inprogress">
                        <i class="bi bi-3-circle-fill"></i>
                    </li>
                    <li class="inprogress">
                        <i class="bi bi-arrow-right-short"></i>
                    </li>
                    <li class="inprogress">
                        <i class="bi bi-4-circle-fill"></i>
                    </li>
                    <li class="inprogress">
                        <i class="bi bi-arrow-right-short"></i>
                    </li>
                    <li class="inprogress">
                        <i class="bi bi-check-circle-fill"></i>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <section class="d-flex justify-content-center align-content-center row text-center">
        <div class="p-3 task d-flex justify-content-center align-content-center row">
            <div class="label">
                The Task
                <div class="underline"></div>
            </div>
        </div>
        <div class="p-1">
            <input type="text" placeholder="What task should we focus on?" id="task-input">

            <div id="task-text" class="task-text"></div>
        </div>
        <div class="p-3">
            <button onclick="StartTimer()" id="start-btn" class="start-btn">Get Started</button>

            <div id="stop-btns" class="stop-btns">
                <button class="pause-btn" onclick="PauseTimer()">Pause Timer</button>
                <button class="stop-btn" onclick="StopTimer()">Stop Timer</button>
            </div>
        </div>
    </section>

</body>

<script>
    const curTime = document.getElementById("time");
    const inputTask = document.getElementById("task-input");
    const textTask = document.getElementById("task-text");
    const startBtn = document.getElementById("start-btn");
    const stopBtns = document.getElementById("stop-btns");

        // Display current time
    function updateClock() {
        const now = new Date();
        const options = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };

        curTime.innerHTML = now.toLocaleString('en-GB', options);
    }

    // Run it every 1000ms (1 second)
    setInterval(updateClock, 1000);
    updateClock(); // Run immediately so there's no 1-second delay

    // Start the 25min timer
    function StartTimer() {
        if (inputTask.value.trim() === "") {
            // Display message    
            return;
        }
        let task = inputTask.value;
        inputTask.style.display = "none";
        textTask.style.display = "block";
        textTask.innerHTML = task;
        stopBtns.style.display = "block";
        startBtn.style.display = "none";

    }

    // Stop the Timer and reset to start
    function StopTimer() {
        inputTask.style.display = "inline-block";
        inputTask.value = null;
        textTask.style.display = "none";
        startBtn.style.display = "inline-block";
        stopBtns.style.display = "none";
    }

    // Pause the timer and change button to resume
    function PauseTimer() {

    }
</script>

</html>