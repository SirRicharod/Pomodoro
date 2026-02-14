<?php
// ===== PHP CONFIGURATION =====
// PHP runs on the server BEFORE this page is sent to your browser.
// We use it here to define our timer settings (in seconds).
// These values get "baked into" the JavaScript when the page loads.
$workDuration = 25 * 60;   // 25 minutes = 1500 seconds
$breakDuration = 5 * 60;   // 5 minutes  = 300 seconds
$longBreakDuration = 15 * 60; // 15 minutes = 900 seconds (after session 4)
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
                    04:20:69
                </div>
            </div>
            <div class="settings" onclick="toggleSettings()">
                <i class="bi bi-gear"></i>
            </div>
        </div>
    </section>

    <!-- ===== SETTINGS PANEL ===== -->
    <div id="settings-panel" class="settings-panel">
        <div class="settings-header">
            <h5>Color Settings</h5>
            <span class="settings-close" onclick="toggleSettings()">&times;</span>
        </div>
        <div class="settings-body">
            <div class="color-row">
                <label for="color-bg">Background</label>
                <input type="color" id="color-bg" data-var="--color-bg" value="#f2e2ba">
            </div>
            <div class="color-row">
                <label for="color-text">Text</label>
                <input type="color" id="color-text" data-var="--color-text" value="#4a1c1b">
            </div>
            <div class="color-row">
                <label for="color-accent">Accent</label>
                <input type="color" id="color-accent" data-var="--color-accent" value="#722f37">
            </div>
            <div class="color-row">
                <label for="color-surface">Surface</label>
                <input type="color" id="color-surface" data-var="--color-surface" value="#f5f5f5">
            </div>
            <div class="color-row">
                <label for="color-success">Start / Complete</label>
                <input type="color" id="color-success" data-var="--color-success" value="#86a861">
            </div>
            <div class="color-row">
                <label for="color-progress">Progress</label>
                <input type="color" id="color-progress" data-var="--color-progress" value="#a44a3f">
            </div>
            <div class="color-row">
                <label for="color-pause">Pause Button</label>
                <input type="color" id="color-pause" data-var="--color-pause" value="#edc150">
            </div>
            <div class="color-row">
                <label for="color-danger">Stop Button</label>
                <input type="color" id="color-danger" data-var="--color-danger" value="#ed5350">
            </div>
            <div class="settings-actions">
                <button class="reset-colors-btn" onclick="resetColors()">Reset to Defaults</button>
            </div>
        </div>
    </div>
    <div id="settings-overlay" class="settings-overlay" onclick="toggleSettings()"></div>

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

            <button onclick="StartNewTask()" id="new-task-btn" class="new-task-btn">Start Another Task</button>
        </div>
    </section>

    <script>
    // ===== DOM ELEMENTS =====
    const curTime = document.getElementById("time");
    const inputTask = document.getElementById("task-input");
    const textTask = document.getElementById("task-text");
    const startBtn = document.getElementById("start-btn");
    const stopBtns = document.getElementById("stop-btns");
    const pauseBtn = document.querySelector(".pause-btn");
    const newTaskBtn = document.getElementById("new-task-btn");
    const timerDisplay = document.querySelector(".timer");
    const progress = document.querySelectorAll(".timer-progress li");

    // ===== TIMER CONFIGURATION (from PHP) =====
    const WORK_DURATION       = <?php echo $workDuration; ?>;      // 25 min
    const BREAK_DURATION      = <?php echo $breakDuration; ?>;     // 5 min
    const LONG_BREAK_DURATION = <?php echo $longBreakDuration; ?>; // 15 min
    const TOTAL_SESSIONS      = <?php echo $totalSessions; ?>;     // 4 cycles

    // ===== STEP MAP =====
    // The progress bar has 9 <li> elements (indices 0-8):
    //   0=circle-1, 1=arrow, 2=circle-2, 3=arrow,
    //   4=circle-3, 5=arrow, 6=circle-4, 7=arrow, 8=checkmark
    //
    // Circles (even: 0,2,4,6) = work sessions (25 min)
    // Arrows  (odd:  1,3,5)   = short breaks  (5 min)
    // Arrow at index 7        = long break    (15 min)
    // Checkmark at index 8    = auto-complete (no timer)
    //
    // We walk through steps 0→1→2→3→4→5→6→7→8 sequentially.
    function getStepDuration(stepIndex) {
        if (stepIndex % 2 === 0) return WORK_DURATION;        // circle = work
        if (stepIndex === 7)     return LONG_BREAK_DURATION;  // last arrow = long break
        return BREAK_DURATION;                                // other arrows = short break
    }

    function getStepLabel(stepIndex) {
        if (stepIndex % 2 === 0) return "FOCUS";
        if (stepIndex === 7)     return "LONG BREAK";
        return "BREAK";
    }

    // ===== SOUND EFFECTS =====
    // The Audio constructor creates a playable sound object from a file.
    // new Audio("path") loads the file, then .play() plays it.
    // We create them once here so the browser pre-loads the files —
    // if we created them inside tick(), there'd be a delay each time.
    const dingSound     = new Audio("ding.wav");      // plays between steps
    const finishedSound = new Audio("finished.wav");  // plays when all done

    // ===== TIMER STATE =====
    let currentStep = 0;           // which step in the progress bar (0-8)
    let timeRemaining = WORK_DURATION;
    let timerInterval = null;
    let isPaused = false;

    // ===== CLOCK (top-left) =====
    function updateClock() {
        const now = new Date();
        curTime.innerHTML = now.toLocaleString('en-GB', {
            hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
        });
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

    // ===== HELPER: Update progress bar highlights =====
    // Steps before currentStep → completed (green)
    // The currentStep itself   → active (highlighted)
    // Steps after currentStep  → inprogress (dim)
    function updateProgress() {
        progress.forEach((li, i) => {
            li.classList.remove('active', 'completed', 'inprogress');
            if (i < currentStep)       li.classList.add('completed');
            else if (i === currentStep) li.classList.add('active');
            else                        li.classList.add('inprogress');
        });
    }

    // ===== CORE: The "tick" function runs every second =====
    function tick() {
        timeRemaining--;
        updateTimerDisplay();

        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            timerInterval = null;

            // Move to the next step
            currentStep++;
            updateProgress();

            // Play the "ding" sound to signal this step is done.
            // .currentTime = 0 rewinds to the start in case it's
            // still playing from a previous step.
            dingSound.currentTime = 0;
            dingSound.play();

            // Step 8 = checkmark → session complete!
            if (currentStep >= progress.length - 1) {
                completeSession();
                return;
            }

            // Otherwise, start the next step (work or break)
            const label = getStepLabel(currentStep);
            timerDisplay.textContent = label;
            timeRemaining = getStepDuration(currentStep);

            // Brief pause to show the label, then start counting
            setTimeout(() => {
                updateTimerDisplay();
                timerInterval = setInterval(tick, 1000);
            }, 1500);
        }
    }

    // ===== COMPLETE: All sessions done =====
    function completeSession() {
        // Light up the checkmark
        currentStep = progress.length - 1;
        progress.forEach(li => {
            li.classList.remove('active', 'inprogress');
            li.classList.add('completed');
        });

        timerDisplay.textContent = "DONE!";

        // Play the completion sound
        finishedSound.currentTime = 0;
        finishedSound.play();

        // Hide pause/stop, show "Start Another Task"
        stopBtns.style.display = "none";
        newTaskBtn.style.display = "inline-block";
    }

    // ===== START: Begin the pomodoro =====
    function StartTimer() {
        if (inputTask.value.trim() === "") {
            inputTask.style.borderColor = "var(--color-danger)";
            inputTask.setAttribute("placeholder", "Please enter a task first!");
            setTimeout(() => {
                inputTask.style.borderColor = "transparent";
                inputTask.setAttribute("placeholder", "What task should we focus on?");
            }, 2000);
            return;
        }

        inputTask.style.display = "none";
        textTask.style.display = "block";
        textTask.textContent = inputTask.value;
        stopBtns.style.display = "block";
        startBtn.style.display = "none";
        newTaskBtn.style.display = "none";

        // Initialize state at step 0 (first work session)
        currentStep = 0;
        timeRemaining = getStepDuration(0);
        isPaused = false;

        updateTimerDisplay();
        updateProgress();

        timerInterval = setInterval(tick, 1000);
    }

    // ===== STOP: Cancel and reset to default =====
    function StopTimer() {
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }

        isPaused = false;
        currentStep = 0;
        timeRemaining = WORK_DURATION;

        updateTimerDisplay();
        inputTask.style.display = "inline-block";
        inputTask.value = "";
        textTask.style.display = "none";
        startBtn.style.display = "inline-block";
        stopBtns.style.display = "none";
        newTaskBtn.style.display = "none";
        pauseBtn.textContent = "Pause Timer";

        progress.forEach(li => {
            li.classList.remove('active', 'completed');
            li.classList.add('inprogress');
        });
    }

    // ===== NEW TASK: Reset for a fresh session =====
    function StartNewTask() {
        StopTimer();
    }

    // ===== PAUSE / RESUME =====
    function PauseTimer() {
        if (!isPaused) {
            clearInterval(timerInterval);
            timerInterval = null;
            isPaused = true;
            pauseBtn.textContent = "Resume Timer";
        } else {
            isPaused = false;
            pauseBtn.textContent = "Pause Timer";
            timerInterval = setInterval(tick, 1000);
        }
    }

    // ===== SETTINGS PANEL =====
    const settingsPanel = document.getElementById('settings-panel');
    const settingsOverlay = document.getElementById('settings-overlay');
    const colorInputs = document.querySelectorAll('.color-row input[type="color"]');

    // Default colors — must match :root in style.css
    const defaultColors = {
        '--color-text':     '#4a1c1b',
        '--color-accent':   '#722f37',
        '--color-surface':  '#f5f5f5',
        '--color-bg':       '#f2e2ba',
        '--color-success':  '#86a861',
        '--color-progress': '#a44a3f',
        '--color-pause':    '#edc150',
        '--color-danger':   '#ed5350'
    };

    // Toggle the settings panel open/closed
    function toggleSettings() {
        settingsPanel.classList.toggle('open');
        settingsOverlay.classList.toggle('open');
    }

    // Apply a single CSS variable change to the :root element
    function applyColor(varName, value) {
        document.documentElement.style.setProperty(varName, value);
    }

    // When any color picker changes, apply it live and save to localStorage
    colorInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            const varName = e.target.dataset.var;  // e.g. "--color-bg"
            const value = e.target.value;           // e.g. "#ff0000"
            applyColor(varName, value);
            saveColors();
        });
    });

    // Save all current color picker values to localStorage
    function saveColors() {
        const colors = {};
        colorInputs.forEach(input => {
            colors[input.dataset.var] = input.value;
        });
        localStorage.setItem('pomodoroColors', JSON.stringify(colors));
    }

    // Load saved colors from localStorage (runs on page load)
    function loadColors() {
        const saved = localStorage.getItem('pomodoroColors');
        if (saved) {
            const colors = JSON.parse(saved);
            for (const [varName, value] of Object.entries(colors)) {
                applyColor(varName, value);
                // Also update the color picker to show the saved value
                const input = document.querySelector(`input[data-var="${varName}"]`);
                if (input) input.value = value;
            }
        }
    }

    // Reset everything back to the original CSS defaults
    function resetColors() {
        for (const [varName, value] of Object.entries(defaultColors)) {
            applyColor(varName, value);
            const input = document.querySelector(`input[data-var="${varName}"]`);
            if (input) input.value = value;
        }
        localStorage.removeItem('pomodoroColors');
    }

    // Load any saved colors when the page first loads
    loadColors();
    </script>

</body>
</html>