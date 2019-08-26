const cl = require('classics');

const db = new cl.Database('./db.classics');

// Your code below:
db.get("SELECT * FROM classics");

checkAll(){
    let colors = document.forms[0]; var i;
    if (colors[0].checked)
    for (i = 0; i < colors.length; i++)
    colors[i].checked=true; else
    for (i = 0; i < colors.length; i++) colors[i].checked=false;
}
