// Menu functionality and dropdown handling

document.addEventListener('DOMContentLoaded', function() {
    
    // Handle dropdown menus on hover for desktop
    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach(dropdown => {
        // Mouse enter for desktop
        dropdown.addEventListener('mouseenter', function() {
            if (window.innerWidth > 768) {
                const menu = this.querySelector('.dropdown-menu');
                if (menu) {
                    menu.style.opacity = '1';
                    menu.style.visibility = 'visible';
                    menu.style.transform = 'translateY(0)';
                }
            }
        });
        
        // Mouse leave for desktop
        dropdown.addEventListener('mouseleave', function() {
            if (window.innerWidth > 768) {
                const menu = this.querySelector('.dropdown-menu');
                if (menu) {
                    menu.style.opacity = '0';
                    menu.style.visibility = 'hidden';
                    menu.style.transform = 'translateY(-10px)';
                }
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth > 768) {
            dropdowns.forEach(dropdown => {
                const menu = dropdown.querySelector('.dropdown-menu');
                if (menu && !dropdown.contains(e.target)) {
                    menu.style.opacity = '0';
                    menu.style.visibility = 'hidden';
                    menu.style.transform = 'translateY(-10px)';
                }
            });
        }
    });
    
    // Sticky header functionality
    const header = document.querySelector('.main-nav');
    let lastScrollTop = 0;
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 100) {
            header.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
        } else {
            header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        }
        
        // Hide/show header on scroll
        if (scrollTop > lastScrollTop && scrollTop > 200) {
            // Scrolling down
            header.style.transform = 'translateY(-100%)';
        } else {
            // Scrolling up
            header.style.transform = 'translateY(0)';
        }
        
        lastScrollTop = scrollTop;
    });
    
    // Resource category filtering
    const resourceCategories = document.querySelectorAll('.resource-category');
    const resourceItems = document.querySelectorAll('.resource-item');
    
    if (resourceCategories.length > 0) {
        resourceCategories.forEach(category => {
            category.addEventListener('click', function() {
                // Remove active class from all categories
                resourceCategories.forEach(cat => cat.classList.remove('active'));
                // Add active class to clicked category
                this.classList.add('active');
                
                const categoryFilter = this.getAttribute('data-category');
                
                resourceItems.forEach(item => {
                    if (categoryFilter === 'all' || item.getAttribute('data-category') === categoryFilter) {
                        item.style.display = 'block';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, 100);
                    } else {
                        item.style.opacity = '0';
                        item.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });
    }
    
});