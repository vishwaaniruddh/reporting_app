
        // Toggle sidebar
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
        
        // Toggle theme
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = themeToggle.querySelector('i');
        
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-theme');
            
            if (document.body.classList.contains('dark-theme')) {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            } else {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
        });
        
        // Close sidebar when clicking outside
        document.addEventListener('click', (event) => {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickInsideMenuToggle = menuToggle.contains(event.target);
            
            if (!isClickInsideSidebar && !isClickInsideMenuToggle && window.innerWidth < 992) {
                sidebar.classList.remove('show');
            }
        });
        
        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue (in thousands)',
                        data: [12, 19, 15, 22, 18, 25, 30, 28, 32, 35, 40, 38],
                        borderColor: '#4361ee',
                        backgroundColor: 'rgba(67, 97, 238, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            // Client Distribution Chart
            const clientCtx = document.getElementById('clientChart').getContext('2d');
            const clientChart = new Chart(clientCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Corporate', 'Small Business', 'Individual', 'Non-Profit'],
                    datasets: [{
                        data: [35, 25, 30, 10],
                        backgroundColor: [
                            '#4361ee',
                            '#4cc9f0',
                            '#3f37c9',
                            '#4895ef'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    cutout: '70%'
                }
            });
        });
