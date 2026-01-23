// Mobile Navigation Toggle - EXACTLY SAME AS OTHER PAGES
        const mobileToggle = document.getElementById('mobileToggle');
        const navMenu = document.getElementById('navMenu');

        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            mobileToggle.innerHTML = navMenu.classList.contains('active') ?
                '<i class="fas fa-times"></i>' :
                '<i class="fas fa-bars"></i>';
        });

        // Close mobile menu when clicking on a link - EXACTLY SAME AS OTHER PAGES
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
            });
        });

        // CPRI Testing specific JavaScript
        let cpriSubmissions = [];

        function loadCPRITable() {
            fetch('api/fetch_cpri_reports.php')
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        cpriSubmissions = result.data;
                        const tbody = document.getElementById('cpriTableBody');
                        tbody.innerHTML = '';
                        let statusCardsHtml = '';

                        cpriSubmissions.forEach(submission => {
                            let statusClass = '';
                            let statusText = '';

                            switch (submission.status) {
                                case 'approved':
                                    statusClass = 'status-approved';
                                    statusText = 'Approved';
                                    break;
                                case 'pending':
                                    statusClass = 'status-pending';
                                    statusText = 'Pending';
                                    break;
                                case 'rejected':
                                    statusClass = 'status-failed';
                                    statusText = 'Rejected';
                                    break;
                            }

                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${submission.product_id}</td>
                                <td>${submission.product_name}</td>
                                <td>${submission.submission_date}</td>
                                <td>${submission.cpri_reference || 'N/A'}</td>
                                <td>${submission.test_date || 'N/A'}</td>
                                <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-icon btn-view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-icon btn-report" title="Generate Report">
                                            <i class="fas fa-file-alt"></i>
                                        </button>
                                        <button class="btn-icon btn-download" title="Download Certificate">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            `;
                            tbody.appendChild(row);

                            // Also generate the status cards
                            statusCardsHtml += generateStatusCard(submission);
                        });

                        document.getElementById('cpri-status-section').innerHTML += statusCardsHtml;
                    } else {
                        showNotification(result.message, 'error');
                    }
                })
                .catch(error => {
                    showNotification('Failed to fetch CPRI data.', 'error');
                    console.error('Error fetching CPRI data:', error);
                });
        }

        function generateStatusCard(submission) {
            let cardClass = '';
            let statusBadge = '';
            switch (submission.status) {
                case 'approved':
                    cardClass = 'approved';
                    statusBadge = '<span class="status-badge status-approved">Approved</span>';
                    break;
                case 'pending':
                    cardClass = 'pending';
                    statusBadge = '<span class="status-badge status-pending">Pending</span>';
                    break;
                case 'rejected':
                    cardClass = 'rejected';
                    statusBadge = '<span class="status-badge status-failed">Rejected</span>';
                    break;
            }

            return `
            <div class="card cpri-card ${cardClass}">
                <div class="card-header">
                    <div>
                        <div class="card-title">${submission.product_name}</div>
                        <div style="color: var(--medium-gray); font-size: 0.95rem; margin-top: 5px;">
                            Submitted: ${submission.submission_date}
                        </div>
                    </div>
                    ${statusBadge}
                </div>
                <div class="cpri-details-grid">
                    <div class="detail-item">
                        <span class="detail-label">Product ID</span>
                        <span class="detail-value">${submission.product_id}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">CPRI Reference</span>
                        <span class="detail-value">${submission.cpri_reference || 'N/A'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Test Date</span>
                        <span class="detail-value">${submission.test_date || 'N/A'}</span>
                    </div>
                </div>
            </div>`;
        }


        // Modal functionality
        document.getElementById('submitToCPRIBtn').addEventListener('click', () => {
            document.getElementById('submitCPRIModal').style.display = 'flex';
        });

        document.getElementById('generateReportBtn').addEventListener('click', () => {
            showNotification('Generating CPRI Testing Report...', 'success');
            setTimeout(() => {
                showNotification('Report generated successfully! Ready for download.', 'success');
            }, 1500);
        });

        document.getElementById('closeCPRIModal').addEventListener('click', () => {
            document.getElementById('submitCPRIModal').style.display = 'none';
        });

        document.getElementById('cancelCPRIBtn').addEventListener('click', () => {
            document.getElementById('submitCPRIModal').style.display = 'none';
        });

        document.getElementById('saveCPRIBtn').addEventListener('click', () => {
            handleCPRISubmission(false);
        });

        document.getElementById('saveAndReportBtn').addEventListener('click', () => {
            handleCPRISubmission(true);
        });

        function handleCPRISubmission(generateReport) {
            const form = document.getElementById('submitCPRIForm');
            const formData = new FormData(form);

            const cpriProductSelect = document.getElementById('cpriProductSelect');
            const productName = cpriProductSelect.options[cpriProductSelect.selectedIndex].text.split(' - ')[1];
            formData.set('product_name', productName);

            if (!formData.get('product_id') || !formData.get('submission_date') || !formData.get('status')) {
                showNotification('Please fill all required fields', 'error');
                return;
            }

            fetch('api/add_cpri_report.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        showNotification(result.message, 'success');
                        document.getElementById('submitCPRIModal').style.display = 'none';
                        form.reset();
                        loadCPRITable(); // Refresh the table
                    } else {
                        showNotification(result.message, 'error');
                    }
                })
                .catch(error => {
                    showNotification('An error occurred during submission.', 'error');
                    console.error('Submission error:', error);
                });
        }

        // Track status button
        document.getElementById('trackStatusBtn').addEventListener('click', () => {
            showNotification('Fetching latest status from CPRI...', 'success');

            setTimeout(() => {
                showNotification('Status updated: Dielectric test completed, starting temperature rise test.', 'success');
            }, 2000);
        });

        // Resubmit button
        document.getElementById('resubmitBtn').addEventListener('click', () => {
            if (confirm('Move this product to re-manufacturing for corrections?')) {
                showNotification('Product moved to re-manufacturing queue for corrections.', 'success');
            }
        });

        // Search functionality
        document.getElementById('cpriSearch').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('#cpriTableBody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Add click handlers for report buttons in the table
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-icon.btn-report')) {
                const row = e.target.closest('tr');
                const productId = row.cells[0].textContent;
                const productName = row.cells[1].textContent;
                showNotification(`Generating report for ${productName} (${productId})...`, 'success');
                setTimeout(() => {
                    showNotification(`Report for ${productName} generated successfully!`, 'success');
                }, 1500);
            }

            if (e.target.closest('.btn-icon.btn-download')) {
                const row = e.target.closest('tr');
                const productId = row.cells[0].textContent;
                const productName = row.cells[1].textContent;
                showNotification(`Downloading certificate for ${productName}...`, 'success');
            }

            if (e.target.closest('.btn-icon.btn-view')) {
                const row = e.target.closest('tr');
                const productId = row.cells[0].textContent;
                const productName = row.cells[1].textContent;
                showNotification(`Opening details for ${productName}...`, 'info');
            }
        });

        // Notification function
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 6px;
                color: white;
                font-weight: 500;
                z-index: 10000;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                animation: slideIn 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
            `;

            if (type === 'success') {
                notification.style.backgroundColor = 'var(--success-green)';
                notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            } else if (type === 'error') {
                notification.style.backgroundColor = 'var(--danger-red)';
                notification.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            } else if (type === 'info') {
                notification.style.backgroundColor = 'var(--info-cyan)';
                notification.innerHTML = `<i class="fas fa-info-circle"></i> ${message}`;
            } else {
                notification.style.backgroundColor = 'var(--accent-blue)';
                notification.innerHTML = `<i class="fas fa-bell"></i> ${message}`;
            }

            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);

            // Add CSS for animations
            if (!document.querySelector('#notification-styles')) {
                const style = document.createElement('style');
                style.id = 'notification-styles';
                style.textContent = `
                    @keyframes slideIn {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes slideOut {
                        from { transform: translateX(0); opacity: 1; }
                        to { transform: translateX(100%); opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }
        }

        // Update year in footer
        document.addEventListener('DOMContentLoaded', function() {
            loadCPRITable();
            const currentYear = new Date().getFullYear();
            const yearElement = document.querySelector('.footer-bottom p');
            yearElement.innerHTML = yearElement.innerHTML.replace('2023', currentYear);

            // Add event listeners to all report buttons
            document.querySelectorAll('.btn-report').forEach(btn => {
                btn.addEventListener('click', function() {
                    showNotification('Generating report...', 'info');
                });
            });
        });