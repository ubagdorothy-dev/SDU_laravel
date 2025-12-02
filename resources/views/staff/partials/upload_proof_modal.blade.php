<!-- Upload Proof Modal -->
<div class="modal fade" id="uploadProofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Proof of Completion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadProofForm">
                <div class="modal-body">
                    <input type="hidden" name="training_id" />
                    <div class="mb-3">
                        <label class="form-label">Select file (photo or certificate)</label>
                        <input type="file" name="proof" class="form-control" accept="image/*,.pdf" required />
                    </div>
                    <div id="uploadProofFeedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>