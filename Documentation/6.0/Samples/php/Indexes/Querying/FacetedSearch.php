<?php

namespace RavenDB\Samples\Indexes\Querying;

use RavenDB\Documents\Queries\Facets\AggregationDocumentQueryInterface;
use PHPUnit\Framework\TestCase;
use RavenDB\Documents\DocumentStore;
use RavenDB\Documents\Indexes\AbstractIndexCreationTask;
use RavenDB\Documents\Queries\Facets\AggregationArray;
use RavenDB\Documents\Queries\Facets\Facet;
use RavenDB\Documents\Queries\Facets\FacetAggregation;
use RavenDB\Documents\Queries\Facets\FacetAggregationField;
use RavenDB\Documents\Queries\Facets\FacetAggregationFieldSet;
use RavenDB\Documents\Queries\Facets\FacetBaseArray;
use RavenDB\Documents\Queries\Facets\FacetOperationsInterface;
use RavenDB\Documents\Queries\Facets\FacetOptions;
use RavenDB\Documents\Queries\Facets\FacetResult;
use RavenDB\Documents\Queries\Facets\FacetResultArray;
use RavenDB\Documents\Queries\Facets\FacetSetup;
use RavenDB\Documents\Queries\Facets\FacetTermSortMode;
use RavenDB\Documents\Queries\Facets\FacetValue;
use RavenDB\Documents\Queries\Facets\RangeBuilder;
use RavenDB\Documents\Queries\Facets\RangeFacet;
use RavenDB\Type\StringArray;

class FacetedSearch extends TestCase
{
    public function createSampleData(): void
    {
        $store = new DocumentStore();
        {
            # region camera_sample_data
            // Creating sample data for the examples in this article:
            // ======================================================

            $cameras = [];

            $cameras[] = new Camera ( $manufacturer = "Sony", $cost = 100, $megaPixels = 20.1, $maxFocalLength = 200, $unitsInStock = 10 );
            $cameras[] = new Camera ( $manufacturer = "Sony", $cost = 200, $megaPixels = 29, $maxFocalLength = 250, $unitsInStock = 15 );
            $cameras[] = new Camera ( $manufacturer = "Nikon", $cost = 120, $megaPixels = 22.3, $maxFocalLength = 300, $unitsInStock = 2 );
            $cameras[] = new Camera ( $manufacturer = "Nikon", $cost = 180, $megaPixels = 32, $maxFocalLength = 300, $unitsInStock = 5 );
            $cameras[] = new Camera ( $manufacturer = "Nikon", $cost = 220, $megaPixels = 40, $maxFocalLength = 300, $unitsInStock = 20 );
            $cameras[] = new Camera ( $manufacturer = "Canon", $cost = 200, $megaPixels = 30.4, $maxFocalLength = 400, $unitsInStock = 30 );
            $cameras[] = new Camera ( $manufacturer = "Olympus", $cost = 250, $megaPixels = 32.5, $maxFocalLength = 600, $unitsInStock = 4 );
            $cameras[] = new Camera ( $manufacturer = "Olympus", $cost = 390, $megaPixels = 40, $maxFocalLength = 600, $unitsInStock = 6 );
            $cameras[] = new Camera ( $manufacturer = "Fuji", $cost = 410, $megaPixels = 45, $maxFocalLength = 700, $unitsInStock = 1 );
            $cameras[] = new Camera ( $manufacturer = "Fuji", $cost = 590, $megaPixels = 45, $maxFocalLength = 700, $unitsInStock = 5 );
            $cameras[] = new Camera ( $manufacturer = "Fuji", $cost = 650, $megaPixels = 61, $maxFocalLength = 800, $unitsInStock = 17 );
            $cameras[] = new Camera ( $manufacturer = "Fuji", $cost = 850, $megaPixels = 102, $maxFocalLength = 800, $unitsInStock = 19 );

            $session = $store->openSession();
            try {
                foreach ($cameras as $camera)
                {
                    $session->store($camera);
                }

                $session->saveChanges();
            } finally {
                $session->close();
            }
            # endregion
        }
    }

    public function facetsBasics(): void
    {
        $store = new DocumentStore();
        try {
            # region facets_1
            // Define a list of facets to query by:
            // ====================================
            $facets = [];

            // Define a Facet:
            // ===============
            $facet = new Facet();
            // Specify the index-field for which to get count of documents per unique ITEM
            // e.g. get the number of Camera documents for each unique Brand
            $facet->setFieldName("Brand");
            // Set a display name for this field in the results (optional)
            $facet->setDisplayFieldName("Camera Brand");

            $facets[] = $facet;

            // Define a RangeFacet: for Cameras_ByFeatures_IndexEntry
            // ====================
            $rangeFacet = new RangeFacet();

            // Specify ranges within an index-field in order to get count per RANGE
            // e.g. get the number of Camera documents that cost below 200, between 200 & 400, etc...
            $rangeFacet->setRanges([
                "price < 200",
                "price >= 200 and price <= 400",
                "price >= 400 and price <= 600",
                "price >= 600 and price <= 800",
                "price >= 800"
            ]);

            // Set a display name for this field in the results (optional)
            $rangeFacet->setDisplayFieldName("Camera Price");

            $facets[] = $rangeFacet;

            # endregion

            $session = $store->openSession();
            try {
                # region facets_2
                $results = $session
                     // Query the index
                    ->query(Cameras_ByFeatures_IndexEntry::class, Cameras_ByFeatures::class)
                     // Call 'AggregateBy' to aggregate the data by facets
                     // Pass the defined facets from above
                    ->aggregateBy($facets)
                    ->execute();
                # endregion
            } finally {
                $session->close();
            }

            $session = $store->openSession();
            try {
                # region facets_2_rawQuery
                $results = $session->advanced()
                      // Query the index
                      // Provide the RQL string to the RawQuery method
                     ->rawQuery(Camera::class,
                          "from index 'Cameras/ByFeatures'
                                 select
                                     facet(Brand) as 'Camera Brand',
                                     facet(Price < 200.0,
                                           Price >= 200.0 and Price < 400.0,
                                           Price >= 400.0 and Price < 600.0,
                                           Price >= 600.0 and Price < 800.0,
                                           Price >= 800.0) as 'Camera Price'"
                      )
                     // Execute the query
                    ->executeAggregation();
                # endregion
            } finally {
                $session->close();
            }

            $session = $store->openSession();
            try {
                # region facets_4
                $results = $session->advanced()
                     // Query the index
                    ->documentQuery(Cameras_ByFeatures_IndexEntry::class, Cameras_ByFeatures::class)
                     // Call 'AggregateBy' to aggregate the data by facets
                     // Pass the defined facets from above
                    ->aggregateBy($facets)
                    ->execute();
                # endregion
            } finally {
                $session->close();
            }

            $session = $store->openSession();
            try {
                # region facets_5
                $results = $session
                     // Query the index
                    ->query(Cameras_ByFeatures_IndexEntry::class, Cameras_ByFeatures::class)
                     // Call 'AggregateBy' to aggregate the data by facets
                     // Use a builder as follows:
                    ->aggregateBy(function($builder) {
                        return $builder
                             // Specify the index-field (e.g. 'Brand') for which to get count per unique ITEM
                            ->byField("Brand")
                             // Set a display name for the field in the results (optional)
                            ->withDisplayName("Camera Brand");
                    })
                    ->andAggregateBy(function($builder) {
                        return $builder
                            // Specify ranges within an index field (e.g. 'Price') in order to get count per RANGE
                            ->byRanges([
                                "Price < 200",
                                "Price >= 200 && Price < 400",
                                "Price >= 400 && Price < 600",
                                "Price >= 600 && Price < 800",
                                "Price >= 800"
                            ])
                            // Set a display name for the field in the results (optional)
                            ->withDisplayName("Camera Price");
                    })
                    ->execute();
                # endregion

                # region facets_6
                // The resulting aggregations per display name will contain:
                // =========================================================

                // For the "Camera Brand" Facet:
                //     "canon"   - Count: 1
                //     "fuji"    - Count: 4
                //     "nikon"   - Count: 3
                //     "olympus" - Count: 2
                //     "sony"    - Count: 2

                // For the "Camera Price" Ranges:
                //     "Price < 200"                      - Count: 3
                //     "Price >= 200.0 and Price < 400.0" - Count: 5
                //     "Price >= 400.0 and Price < 600.0" - Count: 2
                //     "Price >= 600.0 and Price < 800.0" - Count: 1
                //     "Price >= 800.0"                   - Count: 1
                # endregion

                # region facets_7
                // Get facets results for index-field 'Brand' using the display name specified:
                // ============================================================================
                /** @var FacetResult $brandFacets */
                $brandFacets = $results["Camera Brand"];
                $numberOfBrands = count($brandFacets->getValues()); // 5 unique brands

                // Get the aggregated facet value for a specific Brand:
                /** @var FacetValue $facetValue */
                $facetValue = $brandFacets->getValues()[0];
                // The brand name is available in the 'Range' property
                // Note: value is lower-case since the default RavenDB analyzer was used by the index

                $this->assertEquals("canon", $facetValue->getRange());
                // Number of documents for 'Canon' is available in the 'Count' property
                $this->assertEquals(1, $facetValue->getCount());

                // Get facets results for index-field 'Price' using the display name specified:
                // ============================================================================
                /** @var FacetResult $priceFacets */
                $priceFacets = $results["Camera Price"];
                $numberOfRanges = count($priceFacets->getValues()); // 5 different ranges

                // Get the aggregated facet value for a specific Range:
                /** @var FacetValue $facetValue */
                $facetValue = $priceFacets->getValues()[0];
                $this->assertEquals("Price < 200", $facetValue->getRange()); // The range string
                $this->assertEquals(3, $facetValue->getCount()); // Number of documents in this range
                # endregion
            } finally {
                $session->close();
            }

            $session = $store->openSession();
            try {
                # region facets_8
                $filteredResults = $session
                    ->query(Cameras_ByFeatures_IndexEntry::class, Cameras_ByFeatures::class)
                     // Limit query results to the selected brands:
                     ->whereIn("Brand", ["Fuji", "Nikon"])
                    ->aggregateBy($facets)
                    ->execute();
                # endregion
            } finally {
                $session->close();
            }
        } finally {
            $store->close();
        }
    }

    public function facetsOptions(): void
    {
        # region facets_9
        // Define the list of facets to query by:
        // ======================================
        $facetsWithOptions = [];

            // Define a Facet:
        $facet = new Facet();

        // Specify the index-field for which to get count of documents per unique ITEM
        $facet->setFieldName("Brand");

        // Set some facets options
        $options = new FacetOptions();
        // Return the top 3 brands with most items count:
        $options->setPageSize(3);
        $options->setTermSortMode(FacetTermSortMode::countDesc());

        $facet->setOptions($options);

        $facetsWithOptions[] = $facet;
        # endregion

        $store = new DocumentStore();
        try {
            $session = $store->openSession();
            try {
                # region facets_10
                $results = $session
                     // Query the index
                    ->query(Cameras_ByFeatures_IndexEntry::class, Cameras_ByFeatures::class)
                     // Call 'aggregateBy' to aggregate the data by facets
                     // Pass the defined facets from above
                    ->aggregateBy($facetsWithOptions)
                    ->execute();
                # endregion
            } finally {
                $session->close();
            }

            $session = $store->openSession();
            try {
                # region facets_10_rawQuery
                $results = $session->advanced()
                     // Query the index
                     // Provide the RQL string to the RawQuery method
                    ->rawQuery(Camera::class, "from index 'Cameras/ByFeatures'select facet(Brand, \$p0)")
                     // Add the facet options to the "p0" parameter
                    ->addParameter("p0", [ "PageSize" => 3, "TermSortMode" => FacetTermSortMode::countDesc() ])
                     // Execute the query
                    ->executeAggregation();
                # endregion
            } finally {
                $session->close();
            }

            $session = $store->openSession();
            try {
                # region facets_12
                $results = $session->advanced()
                     // Query the index
                    ->documentQuery(Cameras_ByFeatures_IndexEntry::class, Cameras_ByFeatures::class)
                     // Call 'AggregateBy' to aggregate the data by facets
                     // Pass the defined facets from above
                    ->aggregateBy($facetsWithOptions)
                    ->execute();
                # endregion
            } finally {
                $session->close();
            }

            $session = $store->openSession();
            try {
                # region facets_13
                $results = $session
                     // Query the index
                    ->query(Cameras_ByFeatures_IndexEntry::class, Cameras_ByFeatures::class)
                     // Call 'AggregateBy' to aggregate the data by facets
                     // Use a builder as follows:
                    ->aggregateBy(function($builder) {
                        $options = new FacetOptions();
                        // Return the top 3 brands with most items count:
                        $options->setPageSize(3);
                        $options->setTermSortMode(FacetTermSortMode::countDesc());

                         return $builder
                             // Specify an index-field (e.g. 'Brand') for which to get count per unique ITEM
                             ->byField("Brand")
                             // Specify the facets options
                             ->withOptions($options);
                     })
                    ->execute();
                # endregion

                # region facets_14
                // The resulting items will contain:
                // =================================

                // For the "Brand" Facet:
                //     "fuji"    - Count: 4
                //     "nikon"   - Count: 3
                //     "olympus" - Count: 2

                // As requested, only 3 unique items are returned, ordered by documents count descending:
                # endregion

                # region facets_15
                // Get facets results for index-field 'Brand':
                // ===========================================
                /** @var FacetResult $brandFacets */
                $brandFacets = $results["Brand"];
                $numberOfBrands = count($brandFacets->getValues()); // 3 brands

                // Get the aggregated facet value for a specific Brand:
                /** @var FacetValue $facetValue */
                $facetValue = $brandFacets->getValues()[0];
                // The brand name is available in the 'Range' property
                // Note: value is lower-case since the default RavenDB analyzer was used by the index
                $this::assertEquals("fuji", $facetValue->getRange());
                // Number of documents for 'Fuji' is available in the 'Count' property
                $this->assertEquals(4, $facetValue->getCount());
                # endregion
            } finally {
                $session->close();
            }
        } finally {
            $store->close();
        }
    }

    public function facetsAggregations(): void
    {
        # region facets_16
        // Define the list of facets to query by:
        // ======================================
        $facetsWithAggregations = [];

        // Define a Facet:
        // ===============
        $facet = new Facet();
        $facet->setFieldName("Brand");

        $aggregations = new AggregationArray();

        $aggregations->set(
            // Set the aggregation operation:
            FacetAggregation::sum(),
            // Get total number of UnitsInStock for each group of documents per range specified
            [
                // Get total number of UnitsInStock per Brand
                new FacetAggregationField($name = "UnitsInStock")
            ]
        );

        $aggregations->set(FacetAggregation::average(), [
            // Get average Price per Brand
            new FacetAggregationField($name = "Price")
        ]);

        $aggregations->set(FacetAggregation::min(), [
            // Get min Price per Brand
            new FacetAggregationField($name = "Price")
        ]);

        $aggregations->set(FacetAggregation::max(), [
            // Get max MegaPixels per Brand
            new FacetAggregationField($name = "MegaPixels"),
            // Get max MaxFocalLength per Brand
            new FacetAggregationField($name = "MaxFocalLength")
        ]);

        $facet->setAggregations($aggregations);

        // Define a RangeFacet:
        // ====================
        $rangeFacet = new RangeFacet();
        $rangeFacet->setRanges([
            "Price < 200",
            "Price >= 200 && Price < 400",
            "Price >= 400 && Price < 600",
            "Price >= 600 && Price < 800",
            "Price >= 800"
        ]);

        $rangeAggregations = new AggregationArray();

        $rangeAggregations->set(FacetAggregation::sum(), [
            // Get total number of UnitsInStock for each group of documents per range specified
            new FacetAggregationField($name = "UnitsInStock")
        ]);
        $rangeAggregations->set(FacetAggregation::average(), [
            // Get average Price of each group of documents per range specified
            new FacetAggregationField($name = "Price")
        ]);
        $rangeAggregations->set(FacetAggregation::min(), [
            // Get min Price of each group of documents per range specified
            new FacetAggregationField($name = "Price")
        ]);

        $rangeAggregations->set(FacetAggregation::max(), [
            // Get max MegaPixels for each group of documents per range specified
            new FacetAggregationField($name = "MegaPixels"),
            // Get max MaxFocalLength for each group of documents per range specified
            new FacetAggregationField($name = "MaxFocalLength")

        ]);

        $rangeFacet->setAggregations($rangeAggregations);
        # endregion

        $store = new DocumentStore();
        try {
            $session = $store->openSession();
            try {
                # region facets_17
                $results = $session
                     // Query the index
                    ->query(Cameras_ByFeatures_IndexEntry::class, Cameras_ByFeatures::class)
                     // Call 'AggregateBy' to aggregate the data by facets
                     // Pass the defined facets from above
                    ->aggregateBy($facetsWithAggregations)
                    ->execute();
                # endregion
            } finally {
                $session->close();
            }

            $session = $store->openSession();
            try {
                # region facets_17_rawQuery
                $results = $session->advanced()
                     // Query the index
                     // Provide the RQL string to the RawQuery method
                    ->rawQuery(Camera::class,
                     "from index 'Cameras/ByFeatures'
                            select
                                facet(Brand,
                                      sum(UnitsInStock),
                                      avg(Price),
                                      min(Price),
                                      max(MegaPixels),
                                      max(MaxFocalLength)),
                                facet(Price < \$p0,
                                      Price >= \$p1 and Price < \$p2,
                                      Price >= \$p3 and Price < \$p4,
                                      Price >= \$p5 and Price < \$p6,
                                      Price >= \$p7,
                                      sum(UnitsInStock),
                                      avg(Price),
                                      min(Price),
                                      max(MegaPixels),
                                      max(MaxFocalLength))"
                     )
                     // Add the parameters' values
                    ->addParameter("p0", 200.0)
                    ->addParameter("p1", 200.0)
                    ->addParameter("p2", 400.0)
                    ->addParameter("p3", 400.0)
                    ->addParameter("p4", 600.0)
                    ->addParameter("p5", 600.0)
                    ->addParameter("p6", 800.0)
                    ->addParameter("p7", 800.0)
                     // Execute the query
                    ->executeAggregation();
                # endregion
            } finally {
                $session->close();
            }

            $session = $store->openSession();
            try {
                # region facets_19
                $results = $session->advanced()
                     // Query the index
                    ->documentQuery(Cameras_ByFeatures_IndexEntry::class, Cameras_ByFeatures::class)
                     // Call 'AggregateBy' to aggregate the data by facets
                     // Pass the defined facets from above
                    ->aggregateBy($facetsWithAggregations)
                    ->execute();
                # endregion
            } finally {
                $session->close();
            }

            $session = $store->openSession();
            try {
                # region facets_20
                $results = $session
                     // Query the index
                    ->query(Cameras_ByFeatures_IndexEntry::class, Cameras_ByFeatures::class)
                     // Call 'AggregateBy' to aggregate the data by facets
                     // Use a builder as follows:
                    ->aggregateBy(function($builder) {

                        return $builder
                             // Specify an index-field (e.g. 'Brand') for which to get count per unique ITEM
                            ->byField("Brand")
                             // Specify the aggregations per the Brand facet:
                            ->sumOn("UnitsInStock")
                            ->averageOn("Price")
                            ->minOn("Price")
                            ->maxOn("MegaPixels")
                            ->maxOn("MaxFocalLength");
                    })
                    ->andAggregateBy(function($builder) {
                        return $builder
                            // Specify ranges within an index field (e.g. 'Price') in order to get count per RANGE
                            ->byRanges([
                                "Price < 200",
                                "Price >= 200 && Price < 400",
                                "Price >= 400 && Price < 600",
                                "Price >= 600 && Price < 800",
                                "Price >= 800"
                            ])
                             // Specify the aggregations per the Price range:
                            ->sumOn("UnitsInStock")
                            ->averageOn("Price")
                            ->minOn("Price")
                            ->maxOn("MegaPixels")
                            ->maxOn("MaxFocalLength");
                    })
                    ->execute();
                # endregion

                # region facets_21
                // The resulting items will contain (Showing partial results):
                // ===========================================================

                // For the "Brand" Facet:
                //     "canon" Count:1, Sum: 30, Name: UnitsInStock
                //     "canon" Count:1, Min: 200, Average: 200, Name: Price
                //     "canon" Count:1, Max: 30.4, Name: MegaPixels
                //     "canon" Count:1, Max: 400, Name: MaxFocalLength
                //
                //     "fuji" Count:4, Sum: 42, Name: UnitsInStock
                //     "fuji" Count:4, Min: 410, Name: Price
                //     "fuji" Count:4, Max: 102, Name: MegaPixels
                //     "fuji" Count:4, Max: 800, Name: MaxFocalLength
                //
                //     etc.....

                // For the "Price" Ranges:
                //     "Price < 200.0" Count:3, Sum: 17, Name: UnitsInStock
                //     "Price < 200.0" Count:3, Min: 100, Average: 133.33, Name: Price
                //     "Price < 200.0" Count:3, Max: 32, Name: MegaPixels
                //     "Price < 200.0" Count:3, Max: 300, Name: MaxFocalLength
                //
                //     "Price < 200.0 and Price > 400.0" Count:5, Sum: 75, Name: UnitsInStock
                //     "Price < 200.0 and Price > 400.0" Count:5, Min: 200, Average: 252, Name: Price
                //     "Price < 200.0 and Price > 400.0" Count:5, Max: 40, Name: MegaPixels
                //     "Price < 200.0 and Price > 400.0" Count:5, Max: 600, Name: MaxFocalLength
                //
                //     etc.....
                # endregion

                # region facets_22
                // Get results for the 'Brand' Facets:
                // ==========================================
                /** @var FacetResult $brandFacets */
                $brandFacets = $results["Brand"];

                // Get the aggregated facet value for a specific Brand:
                /** @var FacetValue $facetValue */
                $facetValue = $brandFacets->getValues()[0];
                // The brand name is available in the 'Range' property:
                $this->assertEquals("canon", $facetValue->getRange());
                // The index-field on which aggregation was done is in the 'Name' property:
                $this->assertEquals("UnitsInStock", $facetValue->getName());
                // The requested aggregation result:
                $this->assertEquals(30, $facetValue->getSum());

                // Get results for the 'Price' RangeFacets:
                // =======================================
                /** @var FacetResult $priceRangeFacets */
                $priceRangeFacets = $results["Price"];

                // Get the aggregated facet value for a specific Brand:
                /** @var FacetValue $facetValue */
                $facetValue = $priceRangeFacets->getValues()[0];
                // The range string is available in the 'Range' property:
                $this->assertEquals("Price < 200.0", $facetValue->getRange());
                // The index-field on which aggregation was done is in the 'Name' property:
                $this->assertEquals("UnitsInStock", $facetValue->getName());
                // The requested aggregation result:
                $this->assertEquals(17, $facetValue->getSum());
                # endregion
            } finally {
                $session->close();
            }
        } finally {
            $store->close();
        }
    }

    public function facetsFromDocument(): void
    {
        $store = new DocumentStore();
        try {
            $session = $store->openSession();
            try {
                # region facets_23
                // Create a FacetSetup object:
                // ===========================
                $facetSetup = new FacetSetup();
                // Provide the ID of the document in which the facet setup will be stored.
                // This is optional -
                // if not provided then the session will assign an ID for the stored document.
                $facetSetup->setId("customDocumentID");

                // Define Facets and RangeFacets to query by:
                $facetSetup->setFacets([
                    new Facet("Brand")
                ]);


                $facetSetup->setRangeFacets([
                    new RangeFacet(
                        $parent = null,
                        $ranges = [
                            "MegaPixels < 20",
                            "MegaPixels >= 20 && MegaPixels < 30",
                            "MegaPixels >= 30 && MegaPixels < 50",
                            "MegaPixels >= 50"
                        ]
                    )
                ]);

                // Store the facet setup document and save changes:
                // ================================================
                $session->store($facetSetup);
                $session->saveChanges();

                // The document will be stored under the 'FacetSetups' collection
                # endregion
            } finally {
                $session->close();
            }

            $session = $store->openSession();
            try {
                # region facets_24
                $results = $session
                     // Query the index
                    ->query(Cameras_ByFeatures_IndexEntry::class, Cameras_ByFeatures::class)
                     // Call 'AggregateUsing'
                     // Pass the ID of the document that contains your facets setup
                    ->aggregateUsing("customDocumentID")
                    ->execute();
                # endregion
            } finally {
                $session->close();
            }

            $session = $store->openSession();
            try {
                # region facets_24_rawQuery
                $results = $session->advanced()
                     // Query the index
                     // Provide the RQL string to the RawQuery method
                    ->rawQuery(
                        $className = Camera::class,
                        $query = "from index 'Cameras/ByFeatures'                                        
                                select facet(id('customDocumentID'))"
                     )
                     // Execute the query
                    ->executeAggregation();
                # endregion
            } finally {
                $session->close();
            }

            $session = $store->openSession();
            try {
                # region facets_26
                $results = $session->advanced()
                     // Query the index
                    ->documentQuery(Cameras_ByFeatures_IndexEntry::class, Cameras_ByFeatures::class)
                     // Call 'AggregateUsing'
                     // Pass the ID of the document that contains your facets setup
                    ->aggregateUsing("customDocumentID")
                    ->execute();
                # endregion
            } finally {
                $session->close();
            }
        } finally {
            $store->close();
        }
    }
}

# region camera_class
class Camera
{
    private ?string $manufacturer = null;
    private ?float $cost = null;
    private ?float $megaPixels = null;
    private ?int $maxFocalLength = null;
    private ?int $unitsInStock = null;

    public function __construct(
        ?string $manufacturer = null,
        ?float $cost = null,
        ?float $megaPixels = null,
        ?int $maxFocalLength = null,
        ?int $unitsInStock = null,
    )
    {
        $this->manufacturer = $manufacturer;
        $this->cost = $cost;
        $this->megaPixels = $megaPixels;
        $this->maxFocalLength = $maxFocalLength;
        $this->unitsInStock = $unitsInStock;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?string $manufacturer): void
    {
        $this->manufacturer = $manufacturer;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): void
    {
        $this->cost = $cost;
    }

    public function getMegaPixels(): ?float
    {
        return $this->megaPixels;
    }

    public function setMegaPixels(?float $megaPixels): void
    {
        $this->megaPixels = $megaPixels;
    }

    public function getMaxFocalLength(): ?int
    {
        return $this->maxFocalLength;
    }

    public function setMaxFocalLength(?int $maxFocalLength): void
    {
        $this->maxFocalLength = $maxFocalLength;
    }

    public function getUnitsInStock(): ?int
    {
        return $this->unitsInStock;
    }

    public function setUnitsInStock(?int $unitsInStock): void
    {
        $this->unitsInStock = $unitsInStock;
    }
}
# endregion

# region camera_index
class Cameras_ByFeatures_IndexEntry
{
    private ?string $brand = null;
    private ?float $price = null;
    private ?float $megaPixels = null;
    private ?int $maxFocalLength = null;
    private ?int $unitsInStock = null;

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): void
    {
        $this->brand = $brand;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    public function getMegaPixels(): ?float
    {
        return $this->megaPixels;
    }

    public function setMegaPixels(?float $megaPixels): void
    {
        $this->megaPixels = $megaPixels;
    }

    public function getMaxFocalLength(): ?int
    {
        return $this->maxFocalLength;
    }

    public function setMaxFocalLength(?int $maxFocalLength): void
    {
        $this->maxFocalLength = $maxFocalLength;
    }

    public function getUnitsInStock(): ?int
    {
        return $this->unitsInStock;
    }

    public function setUnitsInStock(?int $unitsInStock): void
    {
        $this->unitsInStock = $unitsInStock;
    }
}

class Cameras_ByFeatures extends AbstractIndexCreationTask
{
    public function __construct()
    {
        parent::__construct();

        $this->map =
            "from camera in docs.Cameras " .
            "select new " .
            "{ " .
            " brand = camera.manufacturer," .
            " price = camera.cost," .
            " megaPixels = camera.megaPixels," .
            " maxFocalLength = camera.maxFocalLength," .
            " unitsInStock = camera.unitsInStock" .
            "}";
    }
}
# endregion

// FacetsSyntax

/*
# region syntax_1

public function aggregateBy(Callable|FacetBase|FacetBaseArray|array ...$builderOrFacets): AggregationDocumentQueryInterface;

// You can call it
//   ->aggregateBy(FacetBase $facet);
//   ->aggregateBy(FacetBase $facet1, FacetBase $facet2, ...);
//   ->aggregateBy(FacetBaseArray|array $facets);
//   ->aggregateBy(function(FacetBuilderInterface $builder) { ...});

public function aggregateUsing(?string $facetSetupDocumentId): AggregationDocumentQueryInterface;

# endregion
*/

/*
# region syntax_2
class Facet
{
    private ?string $fieldName = null;
    private ?FacetOptions $options = null;

    // ... getters and setters
}
# endregion
*/

/*
# region syntax_3
class RangeFacet
{
    private StringArray $ranges;
    private ?FacetOptions $options = null;

    // ... getters and setters
}
# endregion
*/

/*
# region syntax_4
class FacetBase
{
    private ?AggregationArray $aggregations = null;
    private ?string $displayFieldName = null;

    // ... getters and setters
}
# endregion
*/

/*
# region syntax_5
interface FacetAggregation
{
    public function isNone(): bool;
    public function isMax(): bool;
    public function isMin(): bool;
    public function isAverage(): bool;
    public function isSum(): bool;

    public static function none(): FacetAggregation;
    public static function max(): FacetAggregation;
    public static function min(): FacetAggregation;
    public static function average(): FacetAggregation;
    public static function sum(): FacetAggregation;
}
# endregion
*/

/*
interface IFoo2
{
    # region syntax_6
    public function byField(string $fieldName): FacetOperationsInterface;
    public function byRanges(?RangeBuilder $range, ?RangeBuilder ...$ranges): FacetOperationsInterface;

    public function withDisplayName(string $displayName): FacetOperationsInterface;
    public function withOptions(FacetOptions $options): FacetOperationsInterface;

    public function sumOn(string $path, ?string $displayName = null): FacetOperationsInterface;
    public function minOn(string $path, ?string $displayName = null): FacetOperationsInterface;
    public function maxOn(string $path, ?string $displayName = null): FacetOperationsInterface;
    public function averageOn(string $path, ?string $displayName = null): FacetOperationsInterface;
    # endregion
}
*/

/*
# region syntax_7
class FacetOptions
{
    private FacetTermSortMode $termSortMode; // default value FacetTermSortMode::valueAsc()
    private bool $includeRemainingTerms = false;
    private int $start = 0;
    private int $pageSize = 0;

    // ... getters and setters
}
# endregion
*/
