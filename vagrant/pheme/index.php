<?php
/**
 * Created by PhpStorm.
 * User: kevinzeidler
 * Date: 5/4/16
 * Time: 6:07 AM
 */

include 'stdlib.php';
include 'Logic.php';
include 'Semaphore.php';

$site = new csite();

// this is a function specific to this site!
initialise_site($site);

$page = new cpage("Semaphore.php: Unit tests");
$site->setPage($page);

//if (!file_exists(file_get_contents("testResults.json"))) {
//    // Loading testResults.json
//    $file =file_get_contents("testResults.json");
//}

$content =<<<EOT
<script>
function isArray(what) {
    return Object.prototype.toString.call(what) === '[object Array]';
}
 $.getJSON( "myScore.json", function( data ) {
   var title = "<h2>"+data[0]+"</h2>";
   var env = "<code>"+data["Code"]+"</code>";
//    $("#root-testing-header").prepend(title);
//     $("#root-testing-header").prepend(env);
        $.each(data, function(k,v) {
             var tbl_body = "";
            if (isArray(data[k])) {
               var tbl_row = "<tr>";
               $.each(this, function(row,col) {
                    // the rows of the test module
                    var rowArr = this;
                    $.each(col, function(i) {
                        // the elements of the row
                        tbl_row += "<td>"+col[i]+"</td>";
                    });
                    tbl_row+= "</tr>";

                });
                tbl_body += "<tr>"+tbl_row+"</tr>";
             } else {

           }
           tbl_body+="</table></div>"
            $("#root-testing-container").append(tbl_body);







        });

    });</script>
EOT;

$page->setContent($content);

$site->render();

?>