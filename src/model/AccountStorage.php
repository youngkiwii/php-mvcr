<?php

interface AccountStorage{

    function checkAuth($login, $password);

    function isUserConnected();

    function isAdminConnected();

    function getUserName();

    function disconnectUser();
}

?>