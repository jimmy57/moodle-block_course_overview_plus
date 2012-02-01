YUI.add('moodle-block_course_overview_plus-collapse', function(Y) {

/**
 * 
 * Initialise this class by calling M.block_course_overview_plus.init
 */
var Collapse = function() {
    Collapse.superclass.constructor.apply(this, arguments);
};
Collapse.prototype = {
    /**
     * Constructor for this class
     * @param {object} config
     */
    initializer : function(params) {
        var i, c;

var courses = params.courses.split(" ");
for(i=0; i<courses.length; i++){
  Y.all('#contract'+courses[i]).on('click', this.collapseCourse, this, courses[i]);
  Y.all('#expand'+courses[i]).on('click', this.expandCourse, this, courses[i]);

}
    },
    collapseCourse : function(e, course) {
        // Prevent the event from refreshing the page
        e.preventDefault();
        Y.one('#extra'+course).addClass('hidden');
        Y.one('#contract'+course).addClass('hidden');
        Y.one('#expand'+course).removeClass('hidden');

        M.util.set_user_preference('courseoverviewpluscontract'+course, 1);
    },
    expandCourse : function(e, course) {
        // Prevent the event from refreshing the page
        e.preventDefault();
        Y.one('#extra'+course).removeClass('hidden');
        Y.one('#contract'+course).removeClass('hidden');
        Y.one('#expand'+course).addClass('hidden');

        M.util.set_user_preference('courseoverviewpluscontract'+course, 0);
    }

};
// Make the colour switcher a fully fledged YUI module
Y.extend(Collapse, Y.Base, Collapse.prototype, {
    NAME : 'My Moodle Collapse Extra Course Info'
});
// Our splash theme namespace
M.block_course_overview_plus = M.block_course_overview_plus || {};
// Initialisation function for the colour switcher
M.block_course_overview_plus.initCollapse = function(params) {
    return new Collapse(params);
}

}, '@VERSION@', {requires:['base','node']});
