
<!DOCTYPE html>
<html>
<head>
    <title>Event Management</title>
</head>
<body>

<h2>Event Management System</h2>

<form id="eventForm">
    <input type="hidden" id="id">

    Event Name:
    <input type="text" id="event_name" required>
    <br><br>

    Event Date:
    <input type="date" id="event_date" required>
    <br><br>

    Description:
    <input type="text" id="description">
    <br><br>

    <button type="submit">Save Event</button>
    <button type="button" onclick="resetForm()">Clear</button>
</form>

<h3>Event List</h3>

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>ID</th>
            <th>Event Name</th>
            <th>Date</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="eventList"></tbody>
</table>

<script src="script.js"></script>

</body>
</html>
