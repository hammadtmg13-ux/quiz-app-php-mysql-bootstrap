// QuizMaster - quiz runner and shared UI logic

(function () {
    'use strict';

    // Quiz page: only runs when QUESTIONS is defined by quiz.php
    if (typeof QUESTIONS !== 'undefined' && document.getElementById('quizBox')) {
        const SECONDS_PER_QUESTION = 30;
        const letters = ['a', 'b', 'c', 'd'];
        const labels = ['A', 'B', 'C', 'D'];

        let current = 0;
        let score = 0;
        let answered = false;
        let timeLeft = SECONDS_PER_QUESTION;
        let timer = null;

        const quizBox = document.getElementById('quizBox');
        const resultBox = document.getElementById('resultBox');
        const questionText = document.getElementById('questionText');
        const optionsBox = document.getElementById('optionsBox');
        const counter = document.getElementById('questionCounter');
        const progressBar = document.getElementById('progressBar');
        const timerCircle = document.getElementById('timerCircle');
        const timerText = document.getElementById('timerText');
        const nextBtn = document.getElementById('nextBtn');

        function stopTimer() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        function startTimer() {
            stopTimer();
            timeLeft = SECONDS_PER_QUESTION;
            timerText.textContent = timeLeft;
            timerCircle.classList.remove('low');
            timer = setInterval(function () {
                timeLeft -= 1;
                timerText.textContent = timeLeft;
                if (timeLeft <= 5) {
                    timerCircle.classList.add('low');
                }
                if (timeLeft <= 0) {
                    stopTimer();
                    lockOptions(null);
                }
            }, 1000);
        }

        function renderQuestion() {
            const q = QUESTIONS[current];
            answered = false;
            nextBtn.disabled = true;
            nextBtn.textContent = current === QUESTIONS.length - 1 ? 'See my result' : 'Next question';
            counter.textContent = 'Question ' + (current + 1) + ' of ' + QUESTIONS.length;
            progressBar.style.width = (current / QUESTIONS.length * 100) + '%';
            questionText.textContent = q.question_text;
            optionsBox.innerHTML = '';

            letters.forEach(function (letter, i) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'option-btn';
                btn.dataset.letter = letter;
                btn.innerHTML = '<strong>' + labels[i] + '.</strong> ' + escapeHtml(q['option_' + letter]);
                btn.addEventListener('click', function () {
                    if (answered) return;
                    stopTimer();
                    lockOptions(letter);
                });
                optionsBox.appendChild(btn);
            });

            startTimer();
        }

        function lockOptions(picked) {
            answered = true;
            const q = QUESTIONS[current];
            const buttons = optionsBox.querySelectorAll('.option-btn');
            buttons.forEach(function (btn) {
                btn.disabled = true;
                if (btn.dataset.letter === q.correct_option) {
                    btn.classList.add('correct');
                } else if (picked && btn.dataset.letter === picked) {
                    btn.classList.add('wrong');
                }
                if (picked && btn.dataset.letter === picked) {
                    btn.classList.add('selected');
                }
            });
            if (picked === q.correct_option) {
                score += 1;
            }
            progressBar.style.width = ((current + 1) / QUESTIONS.length * 100) + '%';
            nextBtn.disabled = false;
        }

        function showResult() {
            quizBox.classList.add('d-none');
            resultBox.classList.remove('d-none');
            document.getElementById('finalScore').textContent = score + '/' + QUESTIONS.length;
            document.getElementById('scoreInput').value = score;
            const percent = Math.round(score * 100 / QUESTIONS.length);
            let message = 'Keep practicing, you will get there!';
            if (percent === 100) {
                message = 'Perfect score! You really know your stuff.';
            } else if (percent >= 80) {
                message = 'Great job! Almost a perfect score.';
            } else if (percent >= 60) {
                message = 'Good work! A little more practice and you will ace it.';
            }
            document.getElementById('resultMessage').textContent = message;
            document.getElementById('resultDetail').textContent = 'You answered ' + score + ' out of ' + QUESTIONS.length + ' questions correctly (' + percent + '%).';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        nextBtn.addEventListener('click', function () {
            if (!answered) return;
            current += 1;
            if (current < QUESTIONS.length) {
                renderQuestion();
            } else {
                showResult();
            }
        });

        renderQuestion();
    }
})();
