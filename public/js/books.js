function fetchBookData(searchQuery = '') {
    let xhr = new XMLHttpRequest();
    let url = 'src/Router/Router.php?controller=BooksController&action=getAllBooks';

    if (searchQuery.trim() !== '') {
        url += '&search=' + encodeURIComponent(searchQuery);
    }

    xhr.open('GET', url, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            let books = JSON.parse(xhr.responseText);
            renderBookTable(books);

            document.getElementById('searchInput').value = searchQuery;
        } else {
            console.error('Error fetching book data:', xhr.statusText);
        }
    };
    xhr.onerror = function () {
        console.error('Error fetching book data.');
    };
    xhr.send();
}

let typingTimer;

const doneTypingInterval = 500;

let searchInput = document.getElementById('searchInput');
searchInput.addEventListener('input', function(event) {
    clearTimeout(typingTimer);

    typingTimer = setTimeout(function() {
        let searchQuery = event.target.value;
        fetchBookData(searchQuery);
    }, doneTypingInterval);
});

function renderBookTable(books) {
    let tableContainer = document.getElementById('bookTableContainer');
    let table = document.createElement('table');
    let headerRow = table.insertRow(0);
    let headerAuthor = headerRow.insertCell(0);
    let headerName = headerRow.insertCell(1);
    let headerDate = headerRow.insertCell(2);

    headerAuthor.textContent = 'Author';
    headerName.textContent = 'Book';
    headerDate.textContent = 'Date';

    books.forEach(function(book) {
        let row = table.insertRow(-1);
        let cellAuthor = row.insertCell(0);
        let cellName = row.insertCell(1);
        let cellDate = row.insertCell(2);

        cellAuthor.textContent = book.author;

        if (!book.bookname) {
            cellName.textContent = 'No books';
        } else {
            cellName.textContent = book.bookname;
        }

        if (!book.date) {
            cellDate.textContent = '-';
        } else {
            cellDate.textContent = book.date;
        }
    });

    tableContainer.innerHTML = '';
    tableContainer.appendChild(table);

    applyAnimationDelays();
}

window.onload = function() {
    fetchBookData();
};

function uploadFile(file) {
    let formData = new FormData();
    formData.append('file', file);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'src/Router/Router.php?controller=BooksController&action=uploadBooKList', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            fetchBookData();

            document.getElementById('xmlFileInput').value = '';
        }
    };

    xhr.onerror = function() {
        console.error('Error uploading file.');
    };

    xhr.send(formData);
}

let fileInput = document.getElementById('xmlFileInput');

fileInput.addEventListener('change', function(event) {
    let file = event.target.files[0];
    uploadFile(file);
});

function applyAnimationDelays() {
    let rows = document.querySelectorAll('table tbody tr');

    rows.forEach(function(row, rowIndex) {
        let cells = row.querySelectorAll('td');

        if (cells.length >= 2) {
            cells[0].style.animationDelay = `${rowIndex * 0.3}s`;

            cells[1].style.animationDelay = `${rowIndex * 0.3 + 0.3}s`;
        }
    });
}

function uploadFromDirectory() {
    let xhr = new XMLHttpRequest();
    let url = 'src/Router/Router.php?controller=BooksController&action=uploadFromDirectory';

    xhr.open('GET', url, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            fetchBookData();
        } else {
            console.error('Error uploading from directory:', xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.error('Error uploading from directory.');
    };
    xhr.send();
}

let loadFromDirectoryButton = document.getElementById('loadFromDirectory');
loadFromDirectoryButton.addEventListener('click', function() {
    uploadFromDirectory();
});