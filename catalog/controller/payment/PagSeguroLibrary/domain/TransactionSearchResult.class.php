<?php
/**
* Represents a page of transactions returned by the transaction search service
*/
class TransactionSearchResult {
	
	/**
	 * Date/time when this search was executed
	 */
	private $date;
	
	/**
	 * Transactions in the current page
	 */
	private $resultsInThisPage;
	
	/**
	 * Total number of pages
	 */
	private $totalPages;
	
	/**
	 * Current page.
	 */
	private $currentPage;
	
	/**
	 * Transactions in this page
	 */
	private $transactions;
	
	/**
	 * @return the current page number
	 */
    public function getCurrentPage() {
        return $this->currentPage;
    }

    /**
      * Sets the current page number
      * @param integer $currentPage
      */
    public function setCurrentPage($currentPage) {
        $this->currentPage = $currentPage;
    }

	/**
	 * @return the date/time when this search was executed
	 */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set the date/time when this search was executed
     * @param date
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * @return the number of Transactions in the current page
     */
    public function getResultsInThisPage() {
        return $this->resultsInThisPage;
    }

    /**
     * Sets the number of Transactions in the current page
     *
     * @param resultsInThisPage
     */
    public function setResultsInThisPage($resultsInThisPage) {
        $this->resultsInThisPage = $resultsInThisPage;
    }

    /**
     * @return the total number of pages
     */
    public function getTotalPages() {
        return $this->totalPages;
    }

    /**
     * Sets the total number of pages
     *
     * @param totalPages
     */
    public function setTotalPages($totalPages) {
        $this->totalPages = $totalPages;
    }

    /**
     * @return the Transactions in this page
     * @see TransactionSummary
     */
    public function getTransactions() {
        return $this->transactions;
    }

    /**
     * Sets the transactions in this page
     *
     * @param transactionSummaries
     */
    public function setTransactions(Array $transactions) {
        $this->transactions = $transactions;
    }
    
    /**
    * @return a string that represents the current object
    */
    public function toString(){
    	return "TransactionSearchResult("
	    	."Date=".$this->date
	    	.", CurrentPage=".$this->currentPage
	    	.", TotalPages=".$this->totalPages
	    	.", Transactions in this page=".$this->resultsInThisPage
    	.")";
    }

}

?>