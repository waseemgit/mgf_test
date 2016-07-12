function convertFormat(rootUrl,controller)
{
    var method = $( "#controllers_format_method" ).val();
    var url = rootUrl+'/'+controller+'/'+method;
    $('.mgf').fadeOut("slow").load(url).fadeIn("slow");
}

function getAverageSalary(rootUrl,controller)
{
    var method = $( "#method_for_average_salary" ).val();
    var url = rootUrl+'/'+controller+'/'+method;
    $('#average_salary').fadeOut("slow").load(url).fadeIn("slow");
}

function getAverageAge(rootUrl,controller)
{
    var method = $( "#method_for_average_age" ).val();
    var url = rootUrl+'/'+controller+'/'+method;
    $('#average_age').fadeOut("slow").load(url).fadeIn("slow");
}
