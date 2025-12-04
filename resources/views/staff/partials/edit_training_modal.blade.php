<!-- Edit Training Modal -->
<div class="modal fade" id="editTrainingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Training</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTrainingForm">
                <div class="modal-body">
                    <input type="hidden" name="id" />
                    <div class="mb-3">
                        <label class="form-label">Training Title</label>
                        <input type="text" name="title" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Venue *</label>
                        <input type="text" name="venue" class="form-control" required />
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nature of Training</label>
                            <select name="nature_of_training" class="form-control" id="edit_nature_of_training">
                                <option value="">Select Nature of Training</option>
                                <option value="Leadership training">Leadership training</option>
                                <option value="Technical skills training">Technical skills training</option>
                                <option value="Management training">Management training</option>
                                <option value="Community development training">Community development training</option>
                                <option value="Advocacy-related training">Advocacy-related training</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="mb-3" id="edit_other_nature_group" style="display: none;">
                                <label class="form-label">Please specify</label>
                                <input type="text" name="nature_of_training_other" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Scope</label>
                            <select name="scope" class="form-control">
                                <option value="">Select Scope</option>
                                <option value="Local">Local</option>
                                <option value="Regional">Regional</option>
                                <option value="National">National</option>
                                <option value="International">International</option>
                            </select>
                        </div>
                    </div>
                    <div id="editTrainingFeedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>