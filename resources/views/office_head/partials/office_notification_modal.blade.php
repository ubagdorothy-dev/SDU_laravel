<!-- Office Notification Modal -->
<div class="modal fade" id="officeNotificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>Send Notification to Office</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="officeNotificationForm">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="notifSubject" class="form-label">Subject (optional)</label>
                        <input type="text" class="form-control" id="notifSubject" name="subject" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Audience</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="audience" id="audienceOffice" value="office_staff" checked>
                                <label class="form-check-label" for="audienceOffice">Staff of Assigned Office</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="audience" id="audienceDirector" value="unit_director">
                                <label class="form-check-label" for="audienceDirector">Unit Director</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notifMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="notifMessage" name="message" rows="4" required maxlength="1000"></textarea>
                    </div>
                    <div id="notifAlert" style="display:none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>