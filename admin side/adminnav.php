<div class="admin-nav">
    <span class="admin-brand">[Restaurant] Admin</span>
    <div class="admin-nav-links">
        <a href="admin.php">Dashboard</a>
        <a href="admin_orders.php">Orders</a>
        <a href="admin_dishes.php">Menu</a>
        <a href="admin_reservations.php">Reservations</a>
        <a href="adminlogout.php">Logout</a>
    </div>
</div>
<script>
    var audioCtx = null;

    function getAudioContext() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        return audioCtx;
    }

    document.addEventListener('click', function() {
        getAudioContext();
    }, { once: true });

    function playAlertBeep() {
        try {
            var ctx = getAudioContext();

            function tone(freq, startTime, duration) {
                var osc = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.35, startTime);
                gain.gain.exponentialRampToValueAtTime(0.001, startTime + duration);
                osc.start(startTime);
                osc.stop(startTime + duration);
            }

            var now = ctx.currentTime;
            tone(880, now, 0.35);
            tone(660, now + 0.3, 0.4);
            tone(880, now + 1.0, 0.35);
            tone(660, now + 1.3, 0.4);
        } catch(e) {}
    }
    function checkNewOrders() {
        fetch('poll_orders.php')
            .then(function(res) { return res.json(); })
            .then(function(data) {
                var lastSeen = localStorage.getItem('lastSeenOrderID');
                if (lastSeen === null) {
                    localStorage.setItem('lastSeenOrderID', data.maxID);
                    return;
                }
                if (data.maxID > parseInt(lastSeen)) {
                    playAlertBeep();
                    localStorage.setItem('lastSeenOrderID', data.maxID);
                }
            })
            .catch(function() { /* fail silently — e.g. logged out, network hiccup */ });
    }

    checkNewOrders();
    setInterval(checkNewOrders, 20000);
</script>