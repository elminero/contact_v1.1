





<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<script src="js/jquery-1.12.0.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <script>

        $(function() {
            var action;
            document.getElementById("tags").focus();

            $("#tags").autocomplete({

                minLength: 2,

                source: "source.php",

                focus: function (event, ui) {
                    $("#topics").val(ui.item.label);
                    return false;
                },

                select: function( event, ui ) {


                    //    $("#results").text(ui.item.value);

                    $("#tagValue").val(ui.item.id);

                    action = "profile.php?id="+ui.item.id;


                    document.getElementById("searchForm").setAttribute("action", action);

                }

            });
        });

    </script>


<form class="navbar-form navbar-right" role="search" id="searchForm" action="search.php" method="post" name="search">
    <div class="form-group">
        <input type="text" id="tags" class="form-control" placeholder="Search For Name" name="name" >
        <input name="value" type="hidden" id="tagValue" />
    </div>
    <button class="btn btn-default">Go</button>
</form>








