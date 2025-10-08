<!-- Add Group Modal -->
<div id="addGroupModal" class="group-modal" style="display: none;">
  <div class="group-modal-content">
    <form id="addGroupForm" method="POST" action="{{ route('groups.store') }}">
      @csrf
      @method('POST')

      <div class="modal-header">
        <h3><i class="fa-solid fa-users-plus"></i> Create New Group</h3>
  
      </div>

      <div class="form-body">
        <div class="form-group">
          <label for="group_name">Group Name</label>
          <input type="text" id="group_name" name="group_name" placeholder="Enter group name..." required>
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" name="description" rows="3" placeholder="Describe this group..."></textarea>
        </div>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn-secondary" onclick="closeAddGroup()">Cancel</button>
        <button type="submit" class="btn-primary">Create Group</button>
      </div>
    </form>
  </div>
</div>

<style>

.group-modal {
  position: fixed;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(15, 23, 42, 0.6);
  backdrop-filter: blur(4px); 
  z-index: 9999;
  animation: fadeIn 0.3s ease-in-out;
}


.group-modal-content {
  background: #1e293b;
  color: #f8fafc;
  border-radius: 14px;
  width: 480px;
  max-width: 95%;
  padding: 0;
  box-shadow: 0 10px 35px rgba(0, 0, 0, 0.6);
  animation: slideUp 0.3s ease-in-out;
}


#addGroupForm {
  display: flex;
  flex-direction: column;
}


.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #0f172a;
  padding: 18px 24px;
  border-bottom: 1px solid #334155;
  border-top-left-radius: 14px;
  border-top-right-radius: 14px;
}

.modal-header h3 {
  font-size: 1.2rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
}

.modal-header i {
  color: #3b82f6;
}

.close-btn {
  background: transparent;
  border: none;
  color: #94a3b8;
  font-size: 18px;
  cursor: pointer;
  transition: color 0.2s ease;
}

.close-btn:hover {
  color: #f1f5f9;
}


.form-body {
  padding: 24px;
}


.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 20px;
}

.form-group label {
  font-size: 0.9rem;
  color: #cbd5e1;
  margin-bottom: 6px;
}

.form-group input,
.form-group textarea {
  background: #0f172a;
  border: 1px solid #334155;
  border-radius: 8px;
  padding: 10px 12px;
  color: #f8fafc;
  font-size: 0.9rem;
  resize: none;
  transition: border 0.2s ease;
}

.form-group input:focus,
.form-group textarea:focus {
  border-color: #3b82f6;
  outline: none;
}


.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 18px 24px;
  border-top: 1px solid #334155;
  background: #1e293b;
  border-bottom-left-radius: 14px;
  border-bottom-right-radius: 14px;
}

.btn-primary,
.btn-secondary {
  padding: 8px 18px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s ease;
}

.btn-primary {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
  color: white;
}

.btn-primary:hover {
  background: linear-gradient(135deg, #2563eb, #1e40af);
}

.btn-secondary {
  background: transparent;
  border: 1px solid #475569;
  color: #94a3b8;
}

.btn-secondary:hover {
  background: #334155;
  color: #f8fafc;
}


@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from { transform: translateY(20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}


@media (max-width: 600px) {
  .group-modal-content {
    width: 95%;
  }
}
</style>

<script>
function openAddGroup() {
  const modal = document.getElementById('addGroupModal');
  modal.style.display = 'flex';
  document.getElementById('group_name').focus();
}

function closeAddGroup() {
  const modal = document.getElementById('addGroupModal');
  const form = document.getElementById('addGroupForm');
  modal.style.display = 'none';
  form.reset();
}


document.addEventListener('click', e => {
  const modal = document.getElementById('addGroupModal');
  if (e.target === modal) closeAddGroup();
});


document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeAddGroup();
});


document.getElementById('addGroupForm').addEventListener('submit', e => {
  const btn = e.target.querySelector('.btn-primary');
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Creating...';
});
</script>
