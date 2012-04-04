<?php
reg("jf/session/name","jFrameworkSession");

reg("jf/session/table/name","jf_sessions");
reg("jf/session/table/ID","ID");
reg("jf/session/table/UserID","UserID");
reg("jf/session/table/SessionID","SessionID");
reg("jf/session/table/LoginTimestamp","LoginDate");
reg("jf/session/table/AccessCount","AccessCount");
reg("jf/session/table/LastAccessTimestamp","LastAccess");
reg("jf/session/table/IP","IP");
reg("jf/session/table/Request","CurrentRequest");

reg("jf/session/state/new","NEW");
reg("jf/session/state/live","LIVE");
reg("jf/session/state/expired","EXPIRED");

reg("jf/users/table/name","jf_users");
reg("jf/users/table/Username","Username");
reg("jf/users/table/UserID","ID");
reg("jf/users/table/Password","Password");

reg("jf/users/HashConcat","jFramework");
?>