<?php
// ===== PHP CONFIGURATION =====
// PHP runs on the server BEFORE this page is sent to your browser.
// We use it here to define our timer settings (in seconds).
// These values get "baked into" the JavaScript when the page loads.
$workDuration = 25 * 60;   // 25 minutes = 1500 seconds
$breakDuration = 5 * 60;   // 5 minutes  = 300 seconds
$totalSessions = 4;        // 4 pomodoro cycles before done
?>
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
    // ===== DOM ELEMENTS =====
    const curTime = document.getElementById("time");
    const inputTask = document.getElementById("task-input");
    const textTask = document.getElementById("task-text");
    const startBtn = document.getElementById("start-btn");
    const stopBtns = document.getElementById("stop-btns");
    const pauseBtn = document.querySelector(".pause-btn");
    const timerDisplay = document.querySelector(".timer");
    const progress = document.querySelectorAll(".timer-progress li");

    // ===== TIMER CONFIGURATION (from PHP) =====
    // PHP injects these values at page load. Open "View Source" in your
    // browser and you'll see the actual numbers instead of PHP tags!
    const WORK_DURATION  = <?php echo $workDuration; ?>;   // seconds
    const BREAK_DURATION = <?php echo $breakDuration; ?>;   // seconds
    const TOTAL_SESSIONS = <?php echo $totalSessions; ?>;   // cycles

    // ===== TIMER STATE =====
    // These variables track everything about the running timer.
    let timeRemaining = WORK_DURATION; // countdown in seconds
    let timerInterval = null;          // holds the setInterval ID
    let isPaused = false;              // is the timer paused?
    let currentSession = 1;            // which pomodoro are we on? (1-4)
    let isBreak = false;               // are we in a break right now?

    // ===== CLOCK (top-left) =====
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
    setInterval(updateClock, 1000);
    updateClock();

    // ===== HELPER: Update the MM:SS display =====
    function updateTimerDisplay() {
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        timerDisplay.textContent =
            String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
    }

    // ===== HELPER: Update the progress indicators (1→2→3→4→✓) =====
    // The <li> elements are laid out as:
    //   index 0 = circle-1, 1 = arrow, 2 = circle-2, 3 = arrow,
    //   4 = circle-3, 5 = arrow, 6 = circle-4, 7 = arrow, 8 = checkmark
    function updateProgress() {
        progress.forEach(li => {
            li.classList.remove('active', 'completed');
            li.classList.add('inprogress');
        });

        // Mark completed sessions green
        for (let s = 1; s < currentSession; s++) {
            const circleIdx = (s - 1) * 2;
            const arrowIdx  = circleIdx + 1;
            progress[circleIdx].classList.replace('inprogress', 'completed');
            if (arrowIdx < progress.length) {
                progress[arrowIdx].classList.replace('inprogress', 'completed');
            }
        }

        // Highlight the current session
        if (currentSession <= TOTAL_SESSIONS) {
            const currentIdx = (currentSession - 1) * 2;
            progress[currentIdx].classList.replace('inprogress', 'active');
        }

        // If all done, light up the final checkmark
        if (currentSession > TOTAL_SESSIONS) {
            progress[progress.length - 1].classList.replace('inprogress', 'completed');
        }
    }

    // ===== CORE: The "tick" function runs every second =====
    function tick() {
        timeRemaining--;
        updateTimerDisplay();

        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            timerInterval = null;

            if (!isBreak) {
                // A work session just finished
                if (currentSession >= TOTAL_SESSIONS) {
                    // That was the last session — we're done!
                    currentSession++;
                    updateProgress();
                    timerDisplay.textContent = "DONE!";
                    alert("All " + TOTAL_SESSIONS + " pomodoro sessions complete! Great work!");
                    StopTimer();
                    return;
                }
                // Otherwise, switch to a break
                isBreak = true;
                timeRemaining = BREAK_DURATION;
                timerDisplay.textContent = "BREAK";
                setTimeout(() => {
                    updateTimerDisplay();
                    timerInterval = setInterval(tick, 1000);
                }, 1500);
            } else {
                // A break just finished — move to next work session
                isBreak = false;
                currentSession++;
                updateProgress();
                timeRemaining = WORK_DURATION;
                updateTimerDisplay();
                timerInterval = setInterval(tick, 1000);
            }
        }
    }

    // ===== START: Begin the pomodoro =====
    function StartTimer() {
        // Don't start without a task name
        if (inputTask.value.trim() === "") {
            inputTask.style.border = "2px solid var(--stop)";
            inputTask.setAttribute("placeholder", "Please enter a task first!");
            setTimeout(() => {
                inputTask.style.border = "2px solid transparent";
                inputTask.setAttribute("placeholder", "What task should we focus on?");
            }, 2000);
            return;
        }

        // Show the task name, hide the input
        inputTask.style.display = "none";
        textTask.style.display = "block";
        textTask.textContent = inputTask.value;
        stopBtns.style.display = "block";
        startBtn.style.display = "none";

        // Initialize timer state
        timeRemaining = WORK_DURATION;
        currentSession = 1;
        isBreak = false;
        isPaused = false;

        updateTimerDisplay();
        updateProgress();

        // Start the countdown — tick() runs every 1 second
        timerInterval = setInterval(tick, 1000);
    }

    // ===== STOP: Cancel everything and go back to default =====
    function StopTimer() {
        // 1. Clear the running interval
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }

        // 2. Reset all state variables
        isPaused = false;
        isBreak = false;
        currentSession = 1;
        timeRemaining = WORK_DURATION;

        // 3. Reset the display
        updateTimerDisplay();
        inputTask.style.display = "inline-block";
        inputTask.value = "";
        textTask.style.display = "none";
        startBtn.style.display = "inline-block";
        stopBtns.style.display = "none";
        pauseBtn.textContent = "Pause Timer";

        // 4. Reset progress indicators
        progress.forEach(li => {
            li.classList.remove('active', 'completed');
            li.classList.add('inprogress');
        });
    }

    // ===== PAUSE / RESUME: Toggle the countdown =====
    function PauseTimer() {
        if (!isPaused) {
            // Pause — stop the interval but keep timeRemaining
            clearInterval(timerInterval);
            timerInterval = null;
            isPaused = true;
            pauseBtn.textContent = "Resume Timer";
        } else {
            // Resume — restart the interval from where we left off
            isPaused = false;
            pauseBtn.textContent = "Pause Timer";
            timerInterval = setInterval(tick, 1000);
        }
    }
</script>

</html>