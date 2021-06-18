<?php
//session_start();

function connectDB()
{
    //return new mysqli("localhost","root","","database_madrigal_noriega");          
    return new mysqli("sisgenchile.com", "sisgenchile_dbmanager", "--d5!RWN[LIm", "sisgenchile_sisgenfe");
}
