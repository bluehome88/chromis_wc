<?php
require_once('./config.php');
require_once(ROOT_FOLDER . 'lib/header_doc.php');
if (!isAuthenticated('ADMIN', 'DOCTOR')) {
    exit;
}

if (isAdmin()) {
    if (isset($_POST['queryString'])) {
        $queryString = $_POST['queryString'] . "%";

        if (strlen($queryString) > 0) {
            $db = getDBConnection();
            $stmt = $db->prepare("SELECT UserId, Username, Firstname, Lastname, UserType, Active
            FROM users
            WHERE Lastname LIKE ?");
            $stmt->bind_param('s', $queryString);
            $stmt->execute();
            $stmt->bind_result($userId, $username, $firstname, $lastname, $usertype, $active);
            $stmt->store_result();
            echo '<ul>';
            while ($stmt->fetch()) {
                if (isset($_POST['editUser'])) {
                    echo '<li><a href="' . SECURE_URL . EDIT_USERS_ADMIN . '?userid=' . $userId . '">' . $lastname . ', ' . $firstname . '</a></li>';
                } else {
                    echo '<li><a href="' . SECURE_URL . DOC_WORKERS_COMP_FORM . '?id=' . $userId . '">' . $lastname . ', ' . $firstname . '</a></li>';
                }
            }
            $stmt->close();
            $db->close();
            echo '<li onclick="doSearch();">more detail...</li>';
            echo '</ul>';
        }
    } else {
        exit();
    }
}
