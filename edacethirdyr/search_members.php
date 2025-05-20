<?php
require_once 'features.php'; // Make sure this file has the connectDB() function and others

if (isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);
    if ($searchTerm === '') {
        echo displayMembers();
        exit;
    }
    echo searchMembersByName($searchTerm);
} else {
    echo displayMembers();
}

function searchMembersByName($name) {
    $conn = connectDB();
    $searchTerm = $conn->real_escape_string($name);

    $sql = "SELECT * FROM members WHERE 
            name LIKE '%$searchTerm%'
            OR student_id LIKE '%$searchTerm%'
            LIMIT 50";

    $result = $conn->query($sql);

    if (!$result) {
        return "Database query error: " . $conn->error;
    }

    if ($result->num_rows == 0) {
        return "<p>No members found matching \"" . htmlspecialchars($name) . "\".</p>";
    }

    // Start styled table
    $output = '
    <style>
   .members-table {
    width: 100%;
    margin: 20px 0;
    font-family: Arial, sans-serif;
    background-color: var(--pearl-color);
}

/* Table header - Now using grid to match rows */
.table-header {
    display: grid;
    grid-template-columns: 125px 105px 0.57fr 140px 160px 170px .52fr 188px 140px;
    background-color: gray;
    color: var(--pearl-color);
    font-weight: 600;
    font-size: var(--font-size-m);
    padding: 12px 8px;
    border-bottom: 5px solid var(--orange-color);
    border-top-right-radius: 19px;
    border-top-left-radius: 19px;
    align-items: center;
}

/* Header cells - Match the padding of row cells */
.table-header > div {
    padding: 4px 30px;
    white-space: nowrap;
}

/* Table rows */
.table-row {
    display: grid;
    grid-template-columns: 100px 100px 0.8fr 150px 150px 170px .7fr 190px 170px;
    padding: 12px 8px;
    border-bottom: 1px solid #eee;
    align-items: center;
    background-color: var(--cream-color);
    font-size: 18px;
    font-weight: 500;
}

/* Profile photo */
.member-photo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

/* Cell styling */
.table-row > div {
    padding: 4px 30px;
    white-space: nowrap;
    padding-left: 60px;
}

/* Action buttons */
.cell-actions {
    display: flex;
    gap: 9px;
}

.edit-btn, .delete-btn {
    padding: 5px 8px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 18px;
    color: var(--gray-color);
    background: none;
}

.edit-btn:hover {
    color: var(--orange-color);
    background-color: rgba(255, 115, 0, 0.1);
}

.delete-btn:hover {
    color: var(--orange-color);
    background-color: rgba(255, 61, 61, 0.1);
}

/* No members message */
.no-members {
    padding: 20px;
    text-align: center;
    color: #666;
    grid-column: 1 / -1;
}
    </style>';

   // Add Font Awesome CDN for icons
$output .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';

$output .= '<div class="members-table">
    <div class="table-header">
        <div>Student No.</div>
        <div>Profile</div>
        <div>Name</div>
        <div>Age</div>
        <div>Gender</div>
        <div>Contact</div>
        <div>Address</div>
        <div>Role</div>
        <div>Actions</div>
    </div>';

while ($row = $result->fetch_assoc()) {
    $contact = !empty($row['contact']) ? htmlspecialchars($row['contact']) : 'N/A';
    $address = !empty($row['address']) ? htmlspecialchars($row['address']) : 'N/A';

    $output .= '<div class="table-row">';
    $output .= '<div>' . htmlspecialchars($row['student_id']) . '</div>';
    $output .= '<div><img class="member-photo" src="' . htmlspecialchars($row['profile_picture']) . '" alt="Profile"></div>';
    $output .= '<div>' . htmlspecialchars($row['name']) . '</div>';
    $output .= '<div>' . htmlspecialchars($row['age']) . '</div>';
    $output .= '<div>' . htmlspecialchars($row['gender']) . '</div>';
    $output .= '<div>' . $contact . '</div>';
    $output .= '<div>' . $address . '</div>';
    $output .= '<div>' . htmlspecialchars($row['role']) . '</div>';
    $output .= '<div class="cell-actions">
                <button class="view-btn" data-id="' . $row['id'] . '" title="View Member">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="edit-btn" data-id="' . $row['id'] . '" title="Edit Member">
                    <i class="fas fa-pen"></i>
                </button>
                <button class="delete-btn" data-id="' . $row['id'] . '" title="Delete Member">
                    <i class="fas fa-trash"></i>
                </button>
            </div>';
    $output .= '</div>';
}

    $conn->close();

    // Add jQuery and your JS here
    $output .= '
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).on("click", ".edit-btn", function() {
    var memberId = $(this).data("id");

    $.ajax({
        url: "get_member.php",
        type: "POST",
        data: { id: memberId },
        success: function(response) {
            var member = JSON.parse(response);

            $("#studentNo").val(member.student_id);

            var nameParts = member.name.split(", ");
            var lastName = nameParts[0] || "";
            var firstAndMiddle = nameParts[1] || "";
            var firstName = "";
            var middleInitial = "";

            if (firstAndMiddle) {
                var firstMiddleParts = firstAndMiddle.split(" ");
                firstName = firstMiddleParts[0] || "";
                middleInitial = firstMiddleParts.length > 1 ? firstMiddleParts[1].replace(".", "") : "";
            }

            $("#firstName").val(firstName);
            $("#middleInitial").val(middleInitial);
            $("#lastName").val(lastName);
            $("#age").val(member.age);
            $("#gender").val(member.gender);
            $("#contact").val(member.contact);
            $("#address").val(member.address);
            $("#role").val(member.role);
            $("#profilePicturePreview").attr("src", member.profile_picture);

            $("#submitAddBtn").data("edit-id", memberId);
            $("#submitAddBtn").text("Update Member");

            $("#membersSection").hide();
            $("#addMemberForm").show();
        },
        error: function() {
            alert("Failed to fetch member data.");
        }
    });
});

$(document).on("click", ".delete-btn", function() {
    if (confirm("Are you sure you want to delete this member?")) {
        var memberId = $(this).data("id");

        $.ajax({
            url: "delete_member.php",
            type: "POST",
            data: { id: memberId },
            success: function(response) {
                if (response.trim() === "success") {
                    location.reload();
                } else {
                    alert("Error deleting member: " + response);
                }
            },
            error: function() {
                alert("Failed to delete member.");
            }
        });
    }
});
</script>
';

    return $output;
}
