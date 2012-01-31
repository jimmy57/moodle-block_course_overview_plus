YUI.add('moodle-block_course_overview_plus-hide', function(Y) {

/**
 * 
 * Initialise this class by calling M.block_course_overview_plus.init
 */
var Hide = function() {
    Hide.superclass.constructor.apply(this, arguments);
};
Hide.prototype = {
    /**
     * Constructor for this class
     * @param {object} config
     */
    initializer : function(params) {
        var i, c;

var courses = params.courses.split(" ");
for(i=0; i<courses.length; i++){
  Y.all('#hider'+courses[i]).on('click', this.hideCourse, this, courses[i], params.editing);
  Y.all('#shower'+courses[i]).on('click', this.showCourse, this, courses[i]);

}
    },
    hideCourse : function(e, course, editing) {
        // Prevent the event from refreshing the page
        e.preventDefault();
        Y.one('#hider'+course).addClass('hidden');
        Y.one('#shower'+course).removeClass('hidden');
        if (editing==0) { 
        Y.one('#course'+course).addClass('hidden');
        Y.one('#hiddencourses').setContent(parseInt(Y.one('#hiddencourses').get("innerHTML"))+1);
        }
        M.util.set_user_preference('courseoverviewplushide'+course, 1);
    },
    showCourse : function(e, course, editing) {
        // Prevent the event from refreshing the page
        e.preventDefault();
        Y.one('#shower'+course).addClass('hidden');
        Y.one('#hider'+course).removeClass('hidden');
        Y.one('#course'+course).removeClass('hidden');

        M.util.set_user_preference('courseoverviewplushide'+course, 0);
    }

};
Y.extend(Hide, Y.Base, Hide.prototype, {
    NAME : 'My Moodle Hide Course'
});
M.block_course_overview_plus = M.block_course_overview_plus || {};
// Initialisation function 
M.block_course_overview_plus.initHide = function(params) {
    return new Hide(params);
}

}, '@VERSION@', {requires:['base','node']});
