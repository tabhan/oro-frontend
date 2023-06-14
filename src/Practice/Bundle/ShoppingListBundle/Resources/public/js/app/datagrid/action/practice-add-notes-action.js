import AddNotesAction from 'oro/datagrid/action/add-notes-action';

const PracticeAddNotesAction = AddNotesAction.extend({
    _handleWidget() {
        if (this.model.get('order_identifier')) {
            return;
        }
        PracticeAddNotesAction.__super__._handleWidget.call(this);
    },
});

export default PracticeAddNotesAction;
