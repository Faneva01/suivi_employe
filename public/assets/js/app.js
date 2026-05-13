/* ============================================
   TechMada RH - Global JavaScript
   ============================================ */

/**
 * Open a modal by ID
 */
function openModal(id) {
    var el = document.getElementById(id);
    if (el) el.classList.add('show');
}

/**
 * Close a modal by ID
 */
function closeModal(id) {
    var el = document.getElementById(id);
    if (el) el.classList.remove('show');
}

/**
 * Set action for RH traiter modal
 */
function setAction(action, id) {
    var actionInput = document.getElementById('action' + id);
    var actionLabel = document.getElementById('actionLabel' + id);
    if (actionInput) actionInput.value = action;
    if (actionLabel) actionLabel.innerText = (action === 'approuvee' ? 'approuver' : 'refuser');
}

/**
 * Fill login form with test credentials
 */
function fillForm(email, password) {
    var emailEl = document.getElementById('email');
    var passEl = document.getElementById('password');
    if (emailEl) emailEl.value = email;
    if (passEl) passEl.value = password;
}

/**
 * Confirm before action
 */
function confirmAction(msg) {
    return confirm(msg || 'Confirmer cette action ?');
}