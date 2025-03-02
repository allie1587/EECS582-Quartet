<!-- Link to display past and upcoming appointments -->
<div class="user-appointments">
            <a href="#" onclick="openAppointmentsModal()">View Upcoming/Past Appointments</a>
        </div>
        <!-- Past and Upcoming Appointment popup -->
        <div id="appointment-modal" class="popup">
            <span class="close-btn" onclick="closeAppointmentsModal()">&times;</span>
            <h2>Your Appointments</h2>
            <h3>Upcoming Appointment</h3>
            <p>Date: March 10, 2025</p>
            <p>Time: 2:00 PM</p>
            <p>Barber: John Doe</p>

            <h3>Past Appointment</h3>
            <p>Date: February 15, 2025</p>
            <p>Time: 11:00 AM</p>
            <p>Barber: John Doe</p>
            </div>
        </div>
</div>
<style>
    /* Popup styling */
    .popup {
            display: none; /* Hidden by default */
            position: fixed;
            top: 10%;
            left: 10%;
            right: 10%;
            bottom: 10%;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            z-index: 1000;
        }

        /* Close button */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
        }
</style>
<script>
    // Open the appointment modal
    function openAppointmentsModal() {
        document.getElementById('appointment-modal').style.display = 'block';
    }
    //Close the appointment modal
    function closeAppointmentsModal() {
        document.getElementById('appointment-modal').style.display = 'none';
    }
</script>
