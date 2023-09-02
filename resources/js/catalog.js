var select = document.getElementById('sortBy');
var form = document.getElementById('Forms');

if(select)
{
   select.addEventListener('change', function() {
        form.submit();
    });
}