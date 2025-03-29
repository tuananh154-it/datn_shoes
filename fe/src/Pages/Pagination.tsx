import React from "react";

interface PaginationProps {
  currentPage: number;
  totalPages: number;
  onPageChange: (page: number) => void;
}

const Pagination: React.FC<PaginationProps> = ({ currentPage, totalPages, onPageChange }) => {
  const pages = Array.from({ length: totalPages }, (_, i) => i + 1);

  return (
    <div className="align-self-center">
      <ul className="pagination text-center justify-content-center">
        {/* Nút Previous */}
        <li className={`page-item ${currentPage === 1 ? "disabled" : ""}`}>
          <a
            className="page-link"
            href="javascript:void(0);"
            onClick={() => onPageChange(currentPage - 1)}
          >
            <i className="flaticon-arrows-1"></i>
          </a>
        </li>

        {/* Các số trang */}
        {pages.map((page) => (
          <li key={page} className={`page-item ${currentPage === page ? "active" : ""}`}>
            <a
              className="page-link"
              href="javascript:void(0);"
              onClick={() => onPageChange(page)}
            >
              {page}
            </a>
          </li>
        ))}

        {/* Nút Next */}
        <li className={`page-item ${currentPage === totalPages ? "disabled" : ""}`}>
          <a
            className="page-link"
            href="javascript:void(0);"
            onClick={() => onPageChange(currentPage + 1)}
          >
            <i className="flaticon-arrows"></i>
          </a>
        </li>
      </ul>
    </div>
  );
};

export default Pagination;
