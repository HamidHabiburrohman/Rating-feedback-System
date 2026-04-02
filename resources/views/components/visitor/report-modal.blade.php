<div class="modal modal--report" id="reportModal">
    <div class="modal__backdrop"></div>
    <div class="modal__container">
        <div class="modal__header">
            <h3 class="modal__title">Report Laboratory</h3>
            <button class="modal__close" aria-label="Close modal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
        <div class="modal__body">
            <form class="report-form" id="reportForm">
                <div class="form-group">
                    <label class="form-label" for="reportReason">Reason for reporting</label>
                    <select class="form-select" id="reportReason" name="reason" required>
                        <option value="" disabled selected>Select a reason</option>
                        <option value="spam">Spam</option>
                        <option value="inappropriate">Inappropriate Content</option>
                        <option value="incorrect">Incorrect Information</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="reportDetails">Additional details</label>
                    <textarea 
                        class="form-textarea" 
                        id="reportDetails" 
                        name="details" 
                        rows="4" 
                        placeholder="Please provide more information about your report..."
                    ></textarea>
                </div>
            </form>
        </div>
        <div class="modal__footer">
            <button class="btn btn--secondary" id="cancelReport">Cancel</button>
            <button class="btn btn--primary" id="submitReport">Submit Report</button>
        </div>
    </div>
</div>